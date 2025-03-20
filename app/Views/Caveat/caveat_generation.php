<?= view('header') ?>

    <style>
        .custom-radio{float: left; display: inline-block; margin-left: 10px; }
        .custom_action_menu{float: left; display: inline-block; margin-left: 10px; }
        .basic_heading{text-align: left!important;color: #31B0D5}
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
        .form-control {
            height: calc(28px + 2px) !important;
            padding: 0.1rem 0.8rem !important;
        }
        .form-group {
            margin-bottom: 1px !important;
        }
        .col-form-label {
            margin-bottom: 0;
            line-height: 1.5;
            padding-top: calc(0.22rem + 2px)!important;
            padding-bottom: calc(0.22rem + 2px)!important;
        }
        .btn_addmore {
            border: 1px solid!important;
            border-radius: 0.25rem!important;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        .h4, h4 {
            font-size: 20px!important;
        }
        hr {
            margin-top: 0px!important;
            margin-bottom: 12px!important;
        }
        .nav-breadcrumb li a, .nav-breadcrumb li a:link, .nav-breadcrumb li a:visited {
            margin-left: -12px!important;
            padding: 9px 41px 9px 15px!important;
        }
        .nav-breadcrumb li a.first {
            border-radius: 5px 0 0 5px;
            padding-left: 4px !important;
        }
        .navbar {
            padding: 0px!important;
        }
        .content-wrapper>.content {
            padding: 0px!important;
        }
        .card {
            display: block !important;
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
                        <div class="col-sm-9"> <h3 class="card-title">Caveat->Generate->Add screen</h3></div>
                        <div class="col-sm-3">
                            <a href="<?=base_url('Caveat/Generation');?>"><button class="btn btn-success btn-sm" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button></a>
                            <a href="<?=base_url('Caveat/Search');?>"><button class="btn btn-info btn-sm" type="button"><i class="fa fa-search-plus" aria-hidden="true"></i></button></a>
                        </div>
                    </div>
                </div>
                <span class="alert alert-error" style="display: none;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <span class="form-response"> </span>
                        </span>

                <div id="dv_content1"   >
                    <input type="hidden" id="fil_hd"/>
                    <div id="show_fil">
                        <!--step form start-->
                        <!-- <div class="card-header p-2" style="background-color: #fff;">
                             <ul class="nav nav-pills">
                                 <li class="nav-item"><a class="nav-link active stepperPosition_1" href="#diary_generation_tab_panel" data-toggle="tab">Basic Details</a></li>
                                 <li class="nav-item"><a class="nav-link stepperPosition_2" href="#petitioner_tab_panel" data-toggle="tab">Petitioner</a></li>
                                 <li class="nav-item"><a class="nav-link stepperPosition_3" href="#respondent_tab_panel" data-toggle="tab">Respondent</a></li>
                             </ul>

                         </div>-->
                        <!-- /.card-header -->
                        <?php
                        $attribute = array('class' => 'form-horizontal caveat_generation_form', 'name' => 'caveat_generation_form', 'id' => 'caveat_generation_form', 'autocomplete' => 'off');
                        echo form_open('#', $attribute);
                        ?>
                        <div class="cardbody">
                            <div class="tab-content-Stop">
                                <div class="activetab-pane" id="diary_generation_tab_panel">
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

                                            $ddl_court_checked = '';
                                            $court_type = !empty($diary_details['ddl_court']) ? $diary_details['ddl_court'] : 1;
                                            $hc_value = !empty($diary_details['dacode']) ? $diary_details['dacode'] : NULL;
                                            foreach ($court_type_list as $row) { ?>
                                            <?php }?>

                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-4">
                                            <div class="form-group row">
                                                <label  class="col-sm-5 col-form-label">Court Type <span class="text-red">*</span> :</label>
                                                <div class="col-sm-7">
                                                    <select name="ddl_court" id="ddl_court" class="form-control">
                                                        <option value="">Select State</option>
                                                        <?php foreach ($court_type_list as $row) {?>
                                                            <option value="<?php echo $row['id'] ?>" <?php if($row['id']=='1') { ?> selected="selected" <?php } ?>><?php echo $row['court_name'] ?></option>
                                                        <?php } ?>
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
                                                        <option value="0">None</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group row">
                                                <label  class="col-sm-5 col-form-label">Bench <span class="text-red">*</span> :</label>
                                                <div class="col-sm-7">
                                                    <select name="ddl_bench" id="ddl_bench" class="form-control">
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
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
                                    </div>
                                    <div class="row ">
                                        <div id="dv_case_no" style="text-align: center"></div>
                                        <div id="dv_parties"></div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-4" style="display: none;" id="mcrc_rw">
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Section :</label>
                                                <div class="col-sm-7">
                                                    <input type="radio" name="rbtn" id="rbtn4" checked>
                                                    <input type="radio" name="rbtn" id="rbtn5"/>
                                                    &nbsp;Bail No: <input type="text" id="bno" size="2" maxlength="2" onkeypress="return onlynumbers(event)"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--<center><div class="btn btn-primary" onclick="setStepper('2')">Next</div></center>-->
                                </div>
                                <!-- /.diary_generation_tab_panel -->


                                <div class="tab-pane" id="petitioner_tab_panel">
                                    <hr/><h4 class="basic_heading"> Caveator Information </h4>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group row">
                                                <label  class="col-sm-5 col-form-label">Caveator<span class="text-red">*</span> :</label>
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
                                                    <label class="col-sm-5 col-form-label">Name<span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="pet_name" name="pet_name" placeholder="Enter Name" onkeypress="return onlyalphab(event)">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Relation :</label>
                                                    <div class="col-sm-7">
                                                        <select id="selprel" name="selprel" class="form-control">
                                                            <option value="">Select</option>
                                                            <option value="S" >Son of</option>
                                                            <option value="D" >Daughter of</option>
                                                            <option value="W" >Wife of</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Father/Husband :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="prel" name="prel" placeholder="Enter Father/Husband" onkeypress="return onlyalpha(event)">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Gender :</label>
                                                    <div class="col-sm-7">
                                                        <select id="psex" name="psex" class="form-control">
                                                            <option value="">Select</option>
                                                            <option value="M">Male</option>
                                                            <option value="F">Female</option>
                                                            <option value="N">N.A.</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Age :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="page" name="page" size="3" maxlength="3" onkeypress="return onlynumbers(event)" placeholder="Enter Age">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Occup./Department :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="pocc" name="pocc"   placeholder="Enter Occupation/Department">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--start individual petitioner address area-->


                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Country <span class="text-red">*</span> :</label>
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
                                                    <label  class="col-sm-5 col-form-label">Address<span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="paddi" name="paddi" placeholder="Enter Address">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Pin Code :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text"  class="form-control" id="ppini" name="ppini" maxlength="6" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Enter Pin">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Tehsil/City<span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="pcityi" name="pcityi" onkeypress="return onlyalpha(event)"  placeholder="Enter City">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">State<span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <select  id="selpsti" name="selpsti" onchange="getDistrict('P',this.id,this.value)" class="form-control">
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
                                                    <label  class="col-sm-5 col-form-label">District<span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <select  id="selpdisi" name="selpdisi"  class="form-control">
                                                            <option value="">Select</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Phone/Mobile :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="pmobi" name="pmobi" maxlength="10" onkeypress="return onlynumbers(event)"  placeholder="Enter contact no.">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Email Id:</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="pemaili"  onblur="remove_apos(this.value,this.id)" placeholder="Enter Email Id">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Total Petitioner(s) :</label>
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
                                                    <label  class="col-sm-5 col-form-label">State Name: <span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="pet_statename" id="pet_statename" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter State">
                                                        <input type="hidden" id="pet_statename_hd"/>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group row clearfix">
                                                    <label class="col-sm-5 col-form-label">Department <span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="pet_deptt" name="pet_deptt" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter Department">
                                                        <input type="hidden" id="pet_deptt_code"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row clearfix">
                                                    <label class="col-sm-5 col-form-label">Post :</label>
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
                                                    <label  class="col-sm-5 col-form-label">Country <span class="text-red">*</span> :</label>
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
                                                    <label class="col-sm-5 col-form-label">Address<span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="paddd" name="paddd" onblur="remove_apos(this.value,this.id)" placeholder="Enter Address">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Pin Code :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text"  class="form-control" id="ppind" name="ppind" maxlength="6" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Enter Pin">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Tehsil/City<span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="pcityd" name="pcityd" onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter City">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">State<span class="text-red">*</span> :</label>
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
                                                    <label  class="col-sm-5 col-form-label">District<span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <select  id="selpdisd" name="selpdisd"  class="form-control">
                                                            <option value="">Select</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-5 col-form-label">Phone/Mobile :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="pmobd" name="pmobd" maxlength="10" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter contact no.">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-5 col-form-label">Email Id <span class="text-red">*</span>:</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="pemaild" name="pemaild" onblur="remove_apos(this.value,this.id)" placeholder="Enter Email Id">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Total Petitioner(s) :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="p_nod" size="3" maxlength="4"  onkeypress="return onlynumbers(event)" value="1" placeholder="Enter Total Petitioner(s)">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!--end department petitioner address area-->

                                    </div>
                                    <!--end party type department for_D_p-->

                                    <!-- <center>
                                         <div class="btn btn-primary" onclick="setStepper('1')">Previous</div>
                                         <div class="btn btn-primary" onclick="setStepper('3')">Next</div>
                                     </center>-->
                                </div>
                                <!-- /.petitioner_tab_panel -->



                                <div class="tab-pane" id="respondent_tab_panel">
                                    <hr/><h4 class="basic_heading"> Caveatee Information </h4>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group row">
                                                <label  class="col-sm-5 col-form-label">Caveatee<span class="text-red">*</span> :</label>
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
                                                    <label  class="col-sm-5 col-form-label">Name<span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="res_name" name="res_name" onkeypress="return onlyalphab(event)" placeholder="Enter Name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-5 col-form-label">Relation :</label>
                                                    <div class="col-sm-7">
                                                        <select id="selrrel" name="selrrel" class="form-control">
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
                                                    <label  class="col-sm-5 col-form-label">Father/Husband :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="rrel" name="rrel" onkeypress="return onlyalpha(event)" placeholder="Enter Father/Husband">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-5 col-form-label">Gender :</label>
                                                    <div class="col-sm-7">
                                                        <select id="rsex" name="rsex" class="form-control">
                                                            <option value="" >Select</option>
                                                            <option value="M" >Male</option>
                                                            <option value="F" >Female</option>
                                                            <option value="N" >N.A.</option>
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
                                                    <label  class="col-sm-5 col-form-label">Occup./Department :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="rocc" placeholder="Enter Occupation/Department">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>




                                        <!--start individual respondent address area-->
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Country<span class="text-red">*</span> :</label>
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
                                                    <label  class="col-sm-5 col-form-label">Address<span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="raddi" name="raddi" onblur="remove_apos(this.value,this.id)" placeholder="Enter Address">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Pin Code :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="rpini" name="rpini" maxlength="6" onkeypress="return onlynumbers(event)" placeholder="Enter Pin">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Tehsil/City<span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="rcityi" name="rcityi" onkeypress="return onlyalpha(event)" placeholder="Enter City">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">State<span class="text-red">*</span> :</label>
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
                                                    <label  class="col-sm-5 col-form-label">District<span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <select id="selrdisi" name="selrdisi" class="form-control">
                                                            <option value="">Select District</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Phone/Mobile :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="rmobi" name="rmobi" maxlength="10" onkeypress="return onlynumbers(event)" placeholder="Enter contact no.">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-5 col-form-label">Email Id:</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="remaili" name="remaili"  placeholder="Enter Email Id">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Total Respondent(s) :</label>
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
                                                    <label class="col-sm-5 col-form-label">State Name<span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="res_statename" name="res_statename" onkeypress="return onlyalphab(event)" placeholder="Enter State">
                                                        <input type="hidden" id="res_statename_hd"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Department<span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="res_deptt" name="res_deptt" onkeypress="return onlyalphab(event)" placeholder="Enter Department">
                                                        <input type="hidden" id="res_deptt_code"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-5 col-form-label">Post :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="res_post" name="res_post" onkeypress="return onlyalphab(event)" placeholder="Enter Post">
                                                        <input type="hidden" id="res_post_code"/>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <!--start department respondent address area-->

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Country<span class="text-red">*</span> :</label>
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
                                                    <label  class="col-sm-5 col-form-label">Address<span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="raddd" name="raddd" onblur="remove_apos(this.value,this.id)" placeholder="Enter Address">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Pin Code :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="rpind" name="rpind" maxlength="6" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter Pin">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Tehsil/City<span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="rcityd" name="rcityd" onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter City">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">State<span class="text-red">*</span> :</label>
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
                                                    <label  class="col-sm-5 col-form-label">District<span class="text-red">*</span> :</label>
                                                    <div class="col-sm-7">
                                                        <select id="selrdisd" name="selrdisd" class="form-control">
                                                            <option value="">Select District</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label  class="col-sm-5 col-form-label">Phone/Mobile :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="rmobd" name="rmobd" maxlength="10" onkeypress="return onlynumbers(event)" placeholder="Enter contact no.">
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
                                                    <label  class="col-sm-5 col-form-label">Total Respondent(s) :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" id="r_nod" size="3" maxlength="4" onkeypress="return onlynumbers(event)" value="1" placeholder="Enter Total Respondent(s)">
                                                    </div>
                                                </div>
                                            </div>


                                        </div>

                                        <!--end department respondent address area-->

                                    </div>
                                    <!--end department respondent for for_D_r-->


                                    <!--start Advocate-->
                                    <hr/><h4 class="basic_heading"> Advocate Details </h4>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group row">
                                                <label  class="col-sm-5 col-form-label">Main Caveator Adv. <span class="text-red">*</span> :</label>
                                                <div class="col-sm-7">
                                                    <select id="padvt" name="padvt" onchange="changeAdvocate(this.id,this.value)" class="form-control" >
                                                        <option value="A">AOR</option>
                                                        <option value="S">State</option>
                                                        <option value="C">Central</option>
                                                        <option value="SS">Petitioner In Person</option>
                                                    </select>
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
                                        <!--<div class="row" id="pcl1">-->
                                        <div class="col-md-4" id="padvno_">
                                            <div class="form-group row">
                                                <label  class="col-sm-5 col-form-label">AOR code/Enrol No <span class="text-red">*</span> :</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="padvno" size="25"  onkeypress="return onlynumbersadv_(event)"  onblur="getAdvocate_for_main('P')"  placeholder="Enrol No.(Non-AOR)">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4" id="padvyr_">
                                            <div class="form-group row">
                                                <label  class="col-sm-5 col-form-label">Year <span class="text-red">*</span> :</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="padvyr" size="4" maxlength="4" onblur="getAdvocate_for_main('P')" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"  disabled="true"/>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group row">
                                                <label  class="col-sm-5 col-form-label">Name</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control"  placeholder="Enter name" id="padvname" size="30" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4" id="padvmob_">
                                            <div class="form-group row">
                                                <label  class="col-sm-5 col-form-label">Mobile</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" placeholder="Enter mobile number" id="padvmob" size="10" maxlength="10" onkeypress="return onlynumbers(event)" disabled="true">
                                                </div>
                                            </div>
                                        </div>
                                        <!--</div>-->
                                        <div class="col-md-4" id="padvemail_">
                                            <div class="form-group row">
                                                <label  class="col-sm-5 col-form-label">Email Id</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="padvemail" name="padvemail" size="30" disabled="true" placeholder="Enter email id">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end Advocate -->





                                    <!--start Advocate respondent-->
                                    <div class="row" style="display: none;">
                                        <div class="col-md-4">
                                            <div class="form-group row">
                                                <label  class="col-sm-5 col-form-label">Main Res. Adv. <span class="text-red">*</span> :</label>
                                                <div class="col-sm-7">
                                                    <select id="radvt" onchange="changeAdvocate(this.id,this.value)" class="form-control" >
                                                        <option value="A">AOR</option>
                                                        <option value="S">State</option>
                                                        <option value="C">Central</option>
                                                        <option value="SS">Petitioner In Person</option>
                                                    </select>
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
                                                <label  class="col-sm-5 col-form-label">AOR code/Enrol No <span class="text-red">*</span> :</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="radvno" size="25"  onblur="getAdvocate_for_main('R')"  placeholder="Enrol No.(Non-AOR)">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4" id="radvyr_">
                                            <div class="form-group row">
                                                <label  class="col-sm-5 col-form-label">Year <span class="text-red">*</span> :</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="radvyr" size="4" maxlength="4" onblur="getAdvocate_for_main('R')" onkeypress="return onlynumbers(event)" disabled="true"/>

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
                                                <label  class="col-sm-5 col-form-label">Mobile</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" placeholder="Enter mobile number" id="radvmob" size="10" maxlength="10" onkeypress="return onlynumbers(event)" disabled="true">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4" id="radvemail_">
                                            <div class="form-group row">
                                                <label  class="col-sm-5 col-form-label">Email Id</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="radvemail" name="radvemail" size="30" disabled="true" placeholder="Enter email id">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end Advocate respondent-->

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group row">
                                                <label  class="col-sm-5 col-form-label">Court Fee :</label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="txt_court_fee" id="txt_court_fee" class="form-control" size="8"  onkeypress="return OnlyNumbersTalwana(event,this.id)" placeholder="Enter Court fee">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <center>
                                        <!--<div class="btn btn-primary" onclick="setStepper('2')">Previous</div>-->
                                        <input type="button" class="btn btn-success" value="Save" onclick="call_save_main('0')" id="svbtn" onkeydown="if (event.keyCode == 13) document.getElementById('svbtn').click()"/>
                                        <input type="button" class="btn btn-danger" value="Reset" onclick="resetPage()">
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
                <br/>
                <center>
                    <div id="show_fil_Ajaxcall"></div>
                </center>

                <!-- /.card -->
            </div>

            </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <link href="<?php echo base_url('autocomplete/autocomplete.css');?>" rel="stylesheet">
    <!--<script src="<?php /*echo base_url('autocomplete/autocomplete.min.js'); */?>"></script>-->
    <script src="<?php echo base_url('autocomplete/autocomplete-ui.min.js'); ?>"></script>
    <script src="<?php echo base_url('caveat/caveat_entry.js'); ?>"></script>
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
                                item =>$.trim(item['district_name'])===$.trim(district_name)
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
                                item =>$.trim(item['district_name'])===$.trim(district_name)
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
                                item =>$.trim(item['district_name'])===$.trim(district_name)
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
                                item =>$.trim(item['district_name'])===$.trim(district_name)
                            );
                            if(singleObj){
                                $('#selpdisd').val('');
                                $("#selpdisd").select2().select2("val", singleObj.id);
                               // $('#selpdisd').val(singleObj.id).select2().trigger("change");
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
        function resetPage(){
            if(confirm('Do you want to reset?')){
                window.location.reload();
            }
        }
    </script>

 <?//=view('sci_main_footer') ?>