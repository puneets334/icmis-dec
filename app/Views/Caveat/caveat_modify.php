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
                                <div class="col-sm-9"> <h3 class="card-title">Caveat->Generate->Modify</h3></div>
                                <div class="col-sm-3">
                                    <a href="<?=base_url('Caveat/Generation');?>"><button class="btn btn-success btn-sm" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button></a>
                                    <a href="<?=base_url('Caveat/Search');?>"><button class="btn btn-info btn-sm" type="button"><i class="fa fa-search-plus	" aria-hidden="true"></i></button></a>
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
                                <?=view('Caveat/caveat_breadcrumb');?>
                                <div class="card-header p-2 text-center" style="background-color: #fff;">
                                    <ul class="nav nav-pills d-block">
                                        <?php if ($is_c_status=='D'){
                                            echo '<p><span id="is_c_status" class="is_c_status text-danger text-center">The searched caveat has been expired. You are not allowed to modify details.</span></p>';
                                        }?>

                                        <?php  $caveat_details= session()->get('caveat_details'); if (!empty($caveat_details)){ ?>

                                            
                                            <span class="text-success"><h3><b>Caveat Number : </b><?=substr($caveat_details['caveat_no'], 0, -4).'/'.substr($caveat_details['caveat_no'],-4);?></h3></span>
                                        <?php }?>
                                    </ul>

                                </div><!-- /.card-header -->
                                <?php
                                $attribute = array('class' => 'form-horizontal caveat_generation_form', 'name' => 'caveat_generation_form', 'id' => 'caveat_generation_form', 'autocomplete' => 'off');
                                echo form_open('#', $attribute);
                                ?>
                                <div class="cardbody">
                                    <div class="tab-contentStop">
                                        <div class="active tab-pane" id="diary_generation_tab_panel">
                                            <div class="form-group row ">
                                                <div class="col-sm-2"></div>
                                                <label class="col-sm-1 col-form-label"> <!--Select Court <span class="text-red">*</span> :--></label>
                                                <div class="col-sm-9">
                                                    <?php
                                                    $stateArr = array();
                                                    if (count($state_list)) {
                                                        foreach ($state_list as $dataRes) {
                                                            $tempArr = array();
                                                            $tempArr['id'] = sanitize(trim($dataRes['cmis_state_id']));
                                                            $tempArr['state_name'] = strtoupper(trim($dataRes['agency_state']));
                                                            $stateArr[] = (object)$tempArr;
                                                        }
                                                    }
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
                                                    $court_type = !empty($fetch_rw['from_court']) ? $fetch_rw['from_court'] : 1;
                                                    $hc_value = !empty($fetch_rw['dacode']) ? $fetch_rw['dacode'] : NULL;
                                                    ?>

                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label  class="col-sm-5 col-form-label">Court Type <span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select name="ddl_court" id="ddl_court" class="form-control">
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
                                                        <label  class="col-sm-5 col-form-label">State<span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select name="ddl_st_agncy" id="ddl_st_agncy" class="form-control">
                                                                <option value="">Select State</option>
                                                                <?php $sel='';
                                                                foreach ($state as $row) {
                                                                    if (isset($row['cmis_state_id'])) {
                                                                        if ($fetch_rw['ref_agency_state_id'] == $row['cmis_state_id']) { $sel = 'selected=selected'; }else{$sel='';}
                                                                        echo '<option ' . $sel . ' value="' . sanitize(($row['cmis_state_id'])) . '">' . sanitize(strtoupper($row['agency_state'])) . '</option>';
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
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label">Case Type <span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select name="ddl_nature" id="ddl_nature" class="form-control">
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
                                                            <input type="radio" name="rbtn" id="rbtn4" checked>438
                                                            <input type="radio" name="rbtn" id="rbtn5"/>439
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
                                                                <option value="I" <?=!empty($pet_rw) && ($pet_rw['ind_dep']=='I') ? 'selected="selected"' :'';?>>Individual</option>
                                                                <option value="D1" <?=!empty($pet_rw) && ($pet_rw['ind_dep']=='D1') ? 'selected="selected"' :'';?>>State Department</option>
                                                                <option value="D2" <?=!empty($pet_rw) && ($pet_rw['ind_dep']=='D2') ? 'selected="selected"' :'';?>>Central Department</option>
                                                                <option value="D3" <?=!empty($pet_rw) && ($pet_rw['ind_dep']=='D3') ? 'selected="selected"' :'';?>>Other Organization</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label for="inputEmail3" class="col-sm-5 col-form-label">Total No. of Pages in File :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" size="4" maxlength="4" id="case_doc" value="<?php echo $fetch_rw['case_pages']?>"  size="3" maxlength="4" onkeypress="return onlynumbers(event)">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!--start party type individual for_I_p-->
                                            <div id="for_I_p" style="display: <?php if (!empty($pet_rw['ind_dep']) && $pet_rw['ind_dep']=='I'){ echo 'block';}else{echo 'none';} ?>">
                                                <div class="row">

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Name<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="pet_name" name="pet_name" value="<?php echo $pet_rw['partyname'] ?? ''?>" placeholder="Enter Name" onkeypress="return onlyalphab(event)">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Relation :</label>
                                                            <div class="col-sm-7">
                                                                <select id="selprel" name="selprel" class="form-control">
                                                                    <option value="">Select</option>
                                                                    <option value="S" <?=!empty($pet_rw) && ($pet_rw['sonof']=='S') ? 'selected="selected"' :'';?>>Son of</option>
                                                                    <option value="D" <?=!empty($pet_rw) && ($pet_rw['sonof']=='D') ? 'selected="selected"' :'';?>>Daughter of</option>
                                                                    <option value="W" <?=!empty($pet_rw) && ($pet_rw['sonof']=='W') ? 'selected="selected"' :'';?>>Wife of</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Father/Husband :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="prel" name="prel" value="<?php echo $pet_rw['prfhname']?>" placeholder="Enter Father/Husband" onkeypress="return onlyalpha(event)">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Gender :</label>
                                                            <div class="col-sm-7">
                                                                <select id="psex" name="psex" class="form-control">
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
                                                            <label  class="col-sm-5 col-form-label">Age :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="page" name="page" value="<?php echo $pet_rw['age']?>" size="3" maxlength="3" onkeypress="return onlynumbers(event)" placeholder="Enter Age">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Occup./Department :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="pocc" name="pocc" value="<?php echo $pet_rw['addr1']?>"  placeholder="Enter Occupation/Department">
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
                                                                        <option value="<?php echo $row['id']; ?>" <?php if (!empty($pet_rw['country']) && $pet_rw['country']==$row['id']){ echo "Selected";}else{ if($row['id']=='96'){ echo "Selected"; } } ?>><?php echo $row['country_name']; ?></option>
                                                                    <?php   }   ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Address<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="paddi" name="paddi" value="<?php echo $pet_rw['addr2']?>" placeholder="Enter Address">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Pin Code :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text"  class="form-control" id="ppini" name="ppini" value="<?php echo $pet_rw['pin']?>" maxlength="6" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Enter Pin">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Tehsil/City<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="pcityi" name="pcityi" value="<?php echo $pet_rw['dstname']?>" onkeypress="return onlyalpha(event)"  placeholder="Enter City">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">State<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select  id="selpsti" name="selpsti" onchange="getDistrict('P',this.id,this.value)" class="form-control" <?php if($pet_rw['country']!='96') echo "disabled";?>>
                                                                    <option value="">Select State</option>
                                                                    <?php  $sel ='';    foreach ($state_list as $row) {
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
                                                            <label  class="col-sm-5 col-form-label">District<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select  id="selpdisi" name="selpdisi"  class="form-control" <?php if($pet_rw['country'] !='96') echo "disabled";?>>
                                                                    <option value="">Select</option>
                                                                    <?php
                                                                    $sel ='';
                                                                    if(!empty($pet_dist_list))
                                                                    {
                                                                        foreach ($pet_dist_list as $row) {
                                                                            if($pet_rw['city']==0){echo '<option  value="' . sanitize(($pet_rw['city'])) . '" selected>Not Mention</option>';     }

                                                                            if ($row['id_no']==$pet_rw['city']) {  $sel = 'selected=selected';  }else{$sel='';}
                                                                            echo '<option ' . $sel . ' value="' . sanitize(($row['id_no'])) . '">' . sanitize(strtoupper($row['name'])) . '</option>';
                                                                        }
                                                                    }																  ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Phone/Mobile <span class="text-red">*</span>:</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="pmobi" name="pmobi" value="<?php echo $pet_rw['contact']?>" maxlength="10" onkeypress="return onlynumbers(event)"  placeholder="Enter contact no.">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Email Id:</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="pemaili" value="<?php echo $pet_rw['email']?>" onblur="remove_apos(this.value,this.id)" placeholder="Enter Email Id">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Total Petitioner(s) :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="p_noi" name="p_noi" value="<?php echo $fetch_rw['pno'];?>" size="3" maxlength="4" onkeypress="return onlynumbers(event)" value="1" placeholder="Enter Total Petitioner(s)">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!--end individual petitioner address area-->
                                            </div>
                                            <!--end party type individual for_I_p-->



                                            <!--start party type department for_D_p-->

                                            <div id="for_D_p" style="display: <?php if ($pet_rw['ind_dep']!='I'){ echo 'block';}else{echo 'none';} ?>">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group row clearfix">
                                                            <label  class="col-sm-5 col-form-label">State Name: <span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" name="pet_statename" id="pet_statename" value="<?php echo $pet_rw['deptname']?>" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter State">
                                                                <input type="hidden" id="pet_statename_hd" value="<?php echo $pet_rw['state_in_name']?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                                    <?php //pr($pet_rw);?>
                                                    <div class="col-md-4">
                                                        <div class="form-group row clearfix">
                                                            <label class="col-sm-5 col-form-label">Department <span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="pet_deptt" name="pet_deptt" value="<?php if(!empty($pet_rw['deptname']) && !empty($pet_rw['partysuff'])) echo trim(str_replace($pet_rw['deptname'],'',$pet_rw['partysuff']));?>" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter Department">
                                                                <input type="hidden" id="pet_deptt_code" value="<?php echo $pet_rw['deptcode'];?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row clearfix">
                                                            <label class="col-sm-5 col-form-label">Post :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" name="pet_post" id="pet_post" value="<?php if(!empty($pet_rw['addr1']) && !empty($pet_rw['addr1'])) echo $pet_rw['addr1'];?>" onkeypress="return onlyalphab(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter Post">
                                                                <input type="hidden" id="pet_post_code" value="<?php echo $pet_rw['authcode']?>">
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
                                                                    foreach ($country as $row) { ?>
                                                                        <option value="<?php echo $row['id']; ?>" <?php if (!empty($pet_rw['country']) && $pet_rw['country']==$row['id']){ echo "Selected";}else{ if($row['id']=='96'){ echo "Selected"; } } ?>><?php echo $row['country_name']; ?></option>
                                                                    <?php   }   ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Address<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="paddd" name="paddd" value="<?php echo $pet_rw['addr2']?>" onblur="remove_apos(this.value,this.id)" placeholder="Enter Address">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Pin Code :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text"  class="form-control" id="ppind" name="ppind" value="<?php echo $pet_rw['pin']?>" maxlength="6" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Enter Pin">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Tehsil/City<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="pcityd" name="pcityd" value="<?php echo $pet_rw['dstname']?>" onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter City">
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
                                                            <label  class="col-sm-5 col-form-label">District<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select  id="selpdisd" name="selpdisd"  class="form-control">
                                                                    <option value="">Select</option>
                                                                    <?php
                                                                    $sel ='';
                                                                    if(!empty($pet_dist_list))
                                                                    {
                                                                        foreach ($pet_dist_list as $row) {
                                                                            if($pet_rw['city']==0){echo '<option  value="' . sanitize(($pet_rw['city'])) . '" selected>Not Mention</option>';     }
                                                                            if ($row['id_no']==$pet_rw['city']) {  $sel = 'selected=selected';  }else{$sel='';}
                                                                            echo '<option ' . $sel . ' value="' . sanitize(($row['id_no'])) . '">' . sanitize(strtoupper($row['name'])) . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Phone/Mobile <span class="text-red">*</span>:</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="pmobd" name="pmobd" value="<?php echo $pet_rw['contact']?>" maxlength="10" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter contact no.">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Email Id <span class="text-red">*</span>:</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="pemaild" name="pemaild" value="<?php echo $pet_rw['email']?>" onblur="remove_apos(this.value,this.id)" placeholder="Enter Email Id">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Total Petitioner(s) :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="p_nod" value="<?php echo $fetch_rw['pno']?>" size="3" maxlength="4"  onkeypress="return onlynumbers(event)" value="1" placeholder="Enter Total Petitioner(s)">
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
                                            <div  id="for_I_r" style="display: <?php if ( !empty($res_rw['ind_dep']) && $res_rw['ind_dep']=='I'){ echo 'block';}else{echo 'none';} ?>">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Name<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="res_name" name="res_name" value="<?php echo $res_rw['partyname'] ?? ''?>" onkeypress="return onlyalphab(event)" placeholder="Enter Name">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Relation :</label>
                                                            <div class="col-sm-7">
                                                                <select id="selrrel" name="selrrel" class="form-control">
                                                                    <option value="">Select</option>
                                                                    <option value="S" <?=!empty($res_rw) && ($res_rw['sonof']=='S') ? 'selected="selected"' :'';?>>Son of</option>
                                                                    <option value="D" <?=!empty($res_rw) && ($res_rw['sonof']=='D') ? 'selected="selected"' :'';?>>Daughter of</option>
                                                                    <option value="W" <?=!empty($res_rw) && ($res_rw['sonof']=='W') ? 'selected="selected"' :'';?>>Wife of</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Father/Husband :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="rrel" name="rrel" value="<?php echo $res_rw['prfhname'] ?? ''?>" onkeypress="return onlyalpha(event)" placeholder="Enter Father/Husband">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Gender :</label>
                                                            <div class="col-sm-7">
                                                                <select id="rsex" name="rsex" class="form-control">
                                                                    <option value="" >Select</option>
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
                                                                <input type="text" class="form-control" id="rage" value="<?php echo $res_rw['age'] ?? ''?>" size="3" maxlength="3" onkeypress="return onlynumbers(event)" placeholder="Enter Age">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Occup./Department :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="rocc" value="<?php echo $res_rw['addr1'] ?? ''?>" placeholder="Enter Occupation/Department">
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
                                                                        <option value="<?php echo $row['id']; ?>" <?php if (!empty($res_rw['country']) && $res_rw['country']==$row['id']){ echo "Selected";}else{ if($row['id']=='96'){ echo "Selected"; } } ?>><?php echo $row['country_name']; ?></option>
                                                                    <?php   }   ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Address<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="raddi" name="raddi" value="<?php echo $res_rw['addr2'] ?? ''?>" onblur="remove_apos(this.value,this.id)" placeholder="Enter Address">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Pin Code :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="rpini" name="rpini" value="<?php echo $res_rw['pin'] ?? ''?>" maxlength="6" onkeypress="return onlynumbers(event)" placeholder="Enter Pin">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Tehsil/City<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="rcityi" name="rcityi" value="<?php echo $res_rw['dstname'] ?? ''?>" onkeypress="return onlyalpha(event)" placeholder="Enter City">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">State<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select id="selrsti" name="selrsti" class="form-control" onchange="getDistrict('R',this.id,this.value)" <?php if(!empty($res_rw['country']) && $res_rw['country']!='96') echo "disabled";?>>
                                                                    <option value="">Select State</option>
                                                                    <?php
                                                                    $sel ='';
                                                                    foreach ($state_list as $row) {
                                                                        if (isset($row['cmis_state_id'])) {
                                                                            if (!empty($res_rw['state']) && $row['cmis_state_id']== $res_rw['state']) 
                                                                            {  
                                                                                $sel = 'selected=selected';  
                                                                            }else
                                                                            {
                                                                                $sel='';
                                                                            }
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
                                                            <label  class="col-sm-5 col-form-label">District<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select id="selrdisi" name="selrdisi" class="form-control" <?php if(!empty($res_rw['country']) && $res_rw['country']!='96') echo "disabled";?>>
                                                                    <option value="">Select District</option>
                                                                    <?php
                                                                    $sel ='';
                                                                    if(!empty($res_dist_list))
                                                                    {
                                                                        foreach ($res_dist_list as $row) {
                                                                            if($res_rw['city']==0){echo '<option  value="' . sanitize(($res_rw['city'])) . '" selected>Not Mention</option>';     }

                                                                            if ($row['id_no']==$res_rw['city']) {  $sel = 'selected=selected';  }else{$sel='';}
                                                                            echo '<option ' . $sel . ' value="' . sanitize(($row['id_no'])) . '">' . sanitize(strtoupper($row['name'])) . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Phone/Mobile <span class="text-red">*</span>:</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="rmobi" name="rmobi" value="<?php echo $res_rw['contact'] ?? ''?>" maxlength="10" onkeypress="return onlynumbers(event)" placeholder="Enter contact no.">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Email Id:</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="remaili" name="remaili" value="<?php echo $res_rw['email'] ?? ''?>" placeholder="Enter Email Id">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Total Respondent(s) :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="r_noi" value="<?php echo $fetch_rw['rno'] ?? ''?>" size="3" maxlength="4" onkeypress="return onlynumbers(event)" value="1" placeholder="Enter Total Respondent(s)">
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>

                                                <!--end individual respondent address area-->


                                            </div>
                                            <!--end party type individual for_I_r-->






                                            <!--start department respondent for for_D_r-->
                                            <div id="for_D_r" style="display: <?php if (!empty($res_rw['ind_dep']) && $res_rw['ind_dep'] !='I'){ echo 'block';}else{echo 'none';} ?>">
                                                <div class="row">

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">State Name<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="res_statename" name="res_statename" value="<?php echo $res_rw['deptname'] ?? '';?>" onkeypress="return onlyalphab(event)" placeholder="Enter State">
                                                                <input type="hidden" id="res_statename_hd" value="<?php echo $res_rw['state_in_name'] ?? ''?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Department<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="res_deptt" name="res_deptt" value="<?php if (!empty($res_rw['deptname']) && !empty($res_rw['partysuff'])) echo trim(str_replace($res_rw['deptname'],'',$res_rw['partysuff']));?>" onkeypress="return onlyalphab(event)" placeholder="Enter Department">
                                                                <input type="hidden" id="res_deptt_code" value="<?php echo $res_rw['deptcode'] ?? ''; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Post<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="res_post" name="res_post" value="<?php echo $res_rw['addr1'] ?? '';?>" onkeypress="return onlyalphab(event)" placeholder="Enter Post">
                                                                <input type="hidden" id="res_post_code" value="<?php echo $res_rw['authcode'] ?? ''?>">
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
                                                                        <option value="<?php echo $row['id']; ?>" <?php if (!empty($res_rw['country']) && $res_rw['country']==$row['id']){ echo "Selected";}else{ if($row['id']=='96'){ echo "Selected"; } } ?>><?php echo $row['country_name']; ?></option>
                                                                    <?php   }   ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Address<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="raddd" name="raddd" value="<?php echo $res_rw['addr2'] ?? ''?>" onblur="remove_apos(this.value,this.id)" placeholder="Enter Address">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Pin Code :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="rpind" name="rpind" value="<?php echo $res_rw['pin'] ?? ''?>" maxlength="6" onkeypress="return onlynumbers(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter Pin">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Tehsil/City<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="rcityd" name="rcityd" value="<?php echo $res_rw['dstname'] ?? ''?>" onkeypress="return onlyalpha(event)" onblur="remove_apos(this.value,this.id)" placeholder="Enter City">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">State<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select id="selrstd" onchange="getDistrict('R',this.id,this.value)" class="form-control" <?php if(!empty($res_rw['country']) &&  $res_rw['country']!='96') echo "disabled";?>>
                                                                    <option value="">Select State</option>
                                                                    <?php
                                                                    $sel ='';
                                                                    foreach ($state_list as $row) {
                                                                        if (isset($row['cmis_state_id'])) {
                                                                            if (!empty($res_rw['state']) && $row['cmis_state_id']==$res_rw['state']) {  $sel = 'selected=selected';  }else{$sel='';}
                                                                            echo '<option ' . $sel . ' value="' . sanitize(($row['cmis_state_id'])) . '">' . sanitize(strtoupper($row['agency_state'])) . '</option>';
                                                                        } ?>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">District<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select id="selrdisd" name="selrdisd" class="form-control" <?php if(!empty($res_rw['country']) && $res_rw['country']!='96') echo "disabled";?>>
                                                                    <option value="">Select District</option>
                                                                    <?php
                                                                    $sel ='';
                                                                    if(!empty($res_dist_list))
                                                                    {
                                                                        foreach ($res_dist_list as $row) {
                                                                            if($res_rw['city']==0){echo '<option  value="' . sanitize(($res_rw['city'])) . '" selected>Not Mention</option>';     }

                                                                            if ($row['id_no']==$res_rw['city']) {  $sel = 'selected=selected';  }else{$sel='';}
                                                                            echo '<option ' . $sel . ' value="' . sanitize(($row['id_no'])) . '">' . sanitize(strtoupper($row['name'])) . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Phone/Mobile <span class="text-red">*</span>:</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="rmobd" name="rmobd" value="<?php echo $res_rw['contact'] ?? ''?>" maxlength="10" onkeypress="return onlynumbers(event)" placeholder="Enter contact no.">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Email Id <span class="text-red">*</span>:</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="remaild" name="remaild" value="<?php echo $res_rw['email'] ?? ''?>" onblur="remove_apos(this.value,this.id)" placeholder="Enter Email Id">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label  class="col-sm-5 col-form-label">Total Respondent(s) :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="r_nod" value="<?php echo $fetch_rw['rno'] ?? ''?>" size="3" maxlength="4" onkeypress="return onlynumbers(event)" value="1" placeholder="Enter Total Respondent(s)">
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>

                                                <!--end department respondent address area-->

                                            </div>
                                            <!--end department respondent for for_D_r-->

                                            <!--start Advocate-->
                                            
                                            <hr/><h4 class="basic_heading"> Main Advocate Information </h4>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label  class="col-sm-5 col-form-label">Main Caveator Adv. <span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select id="padvt" name="padvt" onchange="changeAdvocate(this.id,this.value)" class="form-control" >
                                                                <option value="A" <?php if(trim($fetch_rw['padvt'])=='A') echo "selected";?>>AOR</option>
                                                                <option value="S" <?php if(trim($fetch_rw['padvt']) == 'S') echo "selected";?>>State</option>
                                                                <option value="C" <?php if(trim($fetch_rw['padvt'])=='C') echo "selected";?>>Central</option>                                                                
                                                                <option value="SS" <?php if(trim($fetch_rw['padvt'])=='SS') echo "selected";?>>Petitioner In Person</option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4" id="padv_state">
                                                    <div class="form-group row">
                                                        <label  class="col-sm-5 col-form-label">Enrol State <span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <select name="ddl_pet_adv_state" id="ddl_pet_adv_state"  class="form-control" <?php if($fetch_rw['padvt']!='S') echo "disabled"; ?>>
                                                                <option value="">Select</option>
                                                                <?php
                                                                foreach ($state_list as $row) {
                                                                    if (isset($row['cmis_state_id'])) {
                                                                        if (!empty($petadv_info_rw['state_id']) && $row['cmis_state_id']==$petadv_info_rw['state_id']) {  $sel = 'selected=selected';  }else{$sel='';}
                                                                        echo '<option ' . $sel . ' value="' . sanitize(($row['cmis_state_id'])) . '">' . sanitize(strtoupper($row['agency_state'])) . '</option>';
                                                                    }
                                                                    ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--<div class="row" id="pcl1">-->
                                                <div class="col-md-4" id="padvno_">
                                                    <div class="form-group row">
                                                        <label  class="col-sm-5 col-form-label">AOR code/Enrol No <span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="padvno" value="<?=(!empty($fetch_rw) && (trim($fetch_rw['padvt'])=='A')) ? $petadv_info_rw['aor_code'] ?? '' : $petadv_info_rw['enroll_no'] ?? '';?>" <?php if(!empty($fetch_rw['padvt']) && $fetch_rw['padvt']=='C' && !empty($fetch_rw['padvt']) && $fetch_rw['padvt']!='S') echo "disabled"; ?> size="25"  onkeypress="return onlynumbersadv_(event)"  onblur="getAdvocate_for_main('P')"  placeholder="Enrol No.(Non-AOR)">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4" id="padvyr_">
                                                    <div class="form-group row">
                                                        <label  class="col-sm-5 col-form-label">Year <span class="text-red">*</span> :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="padvyr" value="<?php echo $petadv_info_rw['enroll_date'] ?? ''?>" <?php if(!empty($fetch_rw['padvt']) && $fetch_rw['padvt']=='C' && $fetch_rw['radvt']!='S') echo "disabled"; ?> size="4" maxlength="4" onblur="getAdvocate_for_main('P')" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"  disabled="true"/>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label  class="col-sm-5 col-form-label">Name</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control"  placeholder="Enter name" id="padvname" value="<?php echo $petadv_info_rw['name'] ?? ''?>" size="30" <?php if($fetch_rw['padvt']=='C' && $fetch_rw['radvt']!='S') echo "disabled"; ?>>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4" id="padvmob_">
                                                    <div class="form-group row">
                                                        <label  class="col-sm-5 col-form-label">Mobile</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" placeholder="Enter mobile number" id="padvmob" value="<?php echo $petadv_info_rw['mobile'] ?? ''?>" size="10" maxlength="10" onkeypress="return onlynumbers(event)" disabled="true">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--</div>-->
                                                <div class="col-md-4" id="padvemail_">
                                                    <div class="form-group row">
                                                        <label  class="col-sm-5 col-form-label">Email Id</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="padvemail" name="padvemail" value="<?php echo $petadv_info_rw['email'] ?? ''?>" size="30"  placeholder="Enter email id">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <?php //pr($fetch_rw);?>
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
                                                                        echo'<option value="' . ($row['cmis_state_id']) . '">' . strtoupper($row['agency_state']) . '</option>';
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
                                                            <input type="text" name="txt_court_fee" id="txt_court_fee" class="form-control" value="<?php echo $fetch_rw['court_fee']; ?>" size="3" maxlength="4" onkeypress="return onlynumbers(event)" placeholder="Enter Court fee">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <center> <?=$update_button;?></center>
                                            <br/>
                                        </div>
                                        <!-- /.respondent_tab_panel -->

                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- /.card-body -->
                                <input type="hidden" name="hd_p_barid" id="hd_p_barid" value="<?php echo $fetch_rw['pet_adv_id'];  ?>"/>
                                <input type="hidden" name="hd_r_barid" id="hd_r_barid" value="<?php echo $fetch_rw['res_adv_id'];  ?>"/>
                                <input type="hidden" id="t_h_cno" name="t_h_cno"  size="5" value="<?php echo substr($caveat_details['caveat_no'], 0, -4); ?>"/>
                                <input type="hidden" id="t_h_cyt" name="t_h_cyt"  size="5" value="<?php echo substr($caveat_details['caveat_no'],-4); ?>"/>
                                <?=form_close(); ?>
                            </div> <!--end show_fil-->
                        </div> <!--end dv_content1-->
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
    <script src="<?php echo base_url('caveat/caveat_mod.js'); ?>"></script>

    <script type="text/javascript">

        var court_type = "<?php echo $court_type;?>";

        court_type = parseInt(court_type);
        if(court_type){
            switch (court_type){
                case 1:
                    //high court
                    // $("#radio_selected_court1").prop('checked',true).trigger('click');
                    //get_high_court_list(court_type,hc_value);
                    break;
                case 3:
                    //district court
                    //$("#radio_selected_court3").prop('checked',true).trigger('click');
                    //get_state_list();
                    break;
                case 4:
                    //supreme court
                    //$("#radio_selected_court4").prop('checked',true).trigger('click');
                    // get_sci_case_type();
                    break;
                case 5:
                    //state agency
                    //$("#radio_selected_court5").prop('checked',true).trigger('click');
                    //get_agency_state_list();
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
            update_activate_main('selpt');
            update_activate_main('selrt');
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

  