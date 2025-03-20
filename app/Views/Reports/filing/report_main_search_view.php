<?php  $uri = current_url(true); ?>
<?= view('header') ?>
 
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">
                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Filing / Report</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <span class="alert-danger"><?=\Config\Services::validation()->listErrors()?></span>

                                <?php if(session()->getFlashdata('error')){ ?>
                                    <div class="alert alert-danger">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <?= session()->getFlashdata('error')?>
                                    </div>
                                <?php } else if(session("message_error")){ ?>
                                    <div class="alert alert-danger text-danger" style="color: red;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <?=session("message_error")?>
                                    </div>
                                <?php }else{?>

                                <?php }?>
                            </div>
                            <div class="col-md-12">
                                <div class="card-header p-2" style="background-color: #fff;">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link <?php if(empty($uri->getSegment(3)) ||  ($uri->getSegment(3)=="diary_search")){ echo 'active';}?>" href="#Diary" data-toggle="tab">Diary</a></li>
                                        <li class="nav-item"><a class="nav-link <?php if(($uri->getSegment(3))=="caveat_search"){ echo 'active';}?>" href="#Caveat" id="caveat_search_click" data-toggle="tab">Caveat</a></li>
                                        <li class="nav-item"><a class="nav-link <?php if(($uri->getSegment(3))=='dak_search'){ echo 'active';}?>" href="#DAK" id="dak_search_click" data-toggle="tab">DAK</a></li>
                                        <li class="nav-item"><a class="nav-link <?php if(($uri->getSegment(3))=='fil_trap_search'){ echo 'active';}?>" href="#Fil_Trap" id="filtrap_search_click" data-toggle="tab">Fil Trap</a></li>
                                        <li class="nav-item"><a class="nav-link <?php if(($uri->getSegment(3))=='case_search'){ echo 'active';}?>" href="#Case_Search" id="case_search_click" data-toggle="tab">Case Search</a></li>
                                        <li class="nav-item"><a class="nav-link <?php if(($uri->getSegment(3))=='refiling_search'){ echo 'active';}?>" href="#Refiling" id="refiling_search_click" data-toggle="tab">Refiling</a></li>
                                    </ul>
                                </div>
        <div class="card-body">
        <div class="tab-content">
            <div class="<?php if(empty($uri->getSegment(3)) ||  ($uri->getSegment(3)=="diary_search")){ echo 'active';}?> tab-pane" id="Diary">
        <?php
        $attribute = array('class' => 'form-horizontal','name' => 'diary_search', 'id' => 'diary_search', 'autocomplete' => 'off');
        echo form_open(base_url('Filing/Report/diary_search/'), $attribute); ?>
        <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
             <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="From" class="col-sm-5 col-form-label">From</label>
                                <div class="col-sm-7">
                                    <input type="date" class="form-control" id="from_date" name="from_date" placeholder="From Date" value="<?php if(!empty($formdata['from_date'])){ echo $formdata['from_date']; } ?>">
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-3">

                            <div class="form-group row">
                                <label for="To" class="col-sm-5 col-form-label">To</label>
                                <div class="col-sm-7">
                                    <input type="date" class="form-control" id="to_date" name="to_date" placeholder="TO Date" value="<?php if(!empty($formdata['to_date'])){ echo $formdata['to_date']; } ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Dairy No." class="col-sm-5 col-form-label">Dairy No.</label>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control" id="diary_no" name="diary_no" placeholder="Enter Diary No" value="<?php if(!empty($formdata['diary_no'])){ echo $formdata['diary_no']; }?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Year" class="col-sm-5 col-form-label">Year</label>
                                <div class="col-sm-7">
                                <select class="form-control" name="diary_year" id="diary_year"style="width: 100%;">
    <option value="">Year</option>    <?php
    $yr=date('Y');    for ($year_val = $yr; $year_val >=1947; $year_val--) {    ?>
        <option value="<?php echo $year_val; ?>" <?php if(!empty($formdata["diary_year"]) && $formdata["diary_year"]==$year_val) { ?> selected="selected" <?php } ?>><?php echo $year_val; ?></option>        <?php  }  ?>
</select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row ">

                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Party" class="col-sm-5 col-form-label">Party</label>
                                <div class="col-sm-7">
                                    <select name="ddl_party_type" id="ddl_party_type" class="form-control" style="width: 100%;">
                                        <option value="" <?php if(!empty($formdata['ddl_party_type']) &&  $formdata['ddl_party_type'] == ''){  echo 'selected'; }?>>All</option>
                                        <option value="P" <?php if(!empty($formdata['ddl_party_type']) && $formdata['ddl_party_type'] == 'P'){  echo 'selected'; }?>>Petitioner</option>
                                        <option value="R" <?php if(!empty($formdata['ddl_party_type']) && $formdata['ddl_party_type'] == 'R'){  echo 'selected'; }?>>Respondent</option>
                                        <option value="I" <?php if(!empty($formdata['ddl_party_type']) && $formdata['ddl_party_type']== 'I'){  echo 'selected'; }?>>Impleading</option>
                                        <option value="N" <?php if(!empty($formdata['ddl_party_type']) && $formdata['ddl_party_type'] == 'N'){ echo 'selected'; }?>>Intervenor</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Cause Title" class="col-sm-5 col-form-label">Cause Title</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="cause_title" id="cause_title" value="<?php if(!empty($formdata['cause_title'])){ echo $formdata['cause_title']; } ?>" placeholder="Cause Title">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Case Type" class="col-sm-5 col-form-label">Case Type</label>
                                <div class="col-sm-7">
                                    <select name="case_type_casecode" id="case_type_casecode" class="custom-select rounded-0" style="width: 100%;">
                                    <?php //if(!empty($formdata['case_type_casecode'])){ echo '<option value='.$formdata['case_type_casecode'].'>'.$formdata['case_type_casecode'].'</option>';}?>
                                        <option value="">Select case type</option>
                                        <?php
                                        foreach ($casetype as $row) {
                                            ?>
                                            <option value=<?=sanitize(($row['casecode']))  ?> <?php if(!empty($formdata['case_type_casecode']) && sanitize(($row['casecode']==$formdata['case_type_casecode']))){  echo 'selected=selected'; }?>><?=sanitize(strtoupper($row['casename']))?></option>
                                      <?php  }
                                        ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-3">
                            <div class="form-group row">
                                <label for="Status" class="col-sm-5 col-form-label">Status</label>
                                <div class="col-sm-7">
                                <select name="ddl_status" id="ddl_status" class="form-control " style="width: 100%;">
                                <option value="" <?php if(!empty($formdata['ddl_status']) && ($formdata['ddl_status'] == ' ')) {echo 'selected';}?>>All</option>
                                <option value="P" <?php if(!empty($formdata['ddl_status']) && ($formdata['ddl_status'] == 'P')) {echo 'selected';}?>>Pending</option>
                                <option value="D" <?php if(!empty($formdata['ddl_status']) && ($formdata['ddl_status'] == 'D')) {echo 'selected';}?>>Disposed</option>
                                </select>     </div>
                            </div>
                        </div>

                    </div>
                    <div class="row callout callout-info">
                        <div class="col-sm-6"><?php //if(!empty($formdata['case_type_casecode'])){ echo '<option value='.$formdata['case_type_casecode'].'>'.$formdata['case_type_casecode'].'</option>';}?>
                                        <option value="">Select case type</option>
                                              <div class="icheck-primary d-inline">
                                <input type="checkbox" name="isma" id="isma" <?php if(!empty($formdata['isma'])){ echo 'checked'; } ?> >    
                                        <label for="isma">Exclude Review/Curative/Contempt/MA</label>
                            </div>
                        </div>    <div class="col-sm-6">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" name="is_inperson" id="is_inperson" 
                                <?php if(!empty($formdata['is_inperson'])){ echo 'checked'; } ?> >            <label for="is_inperson">Show Only In-Persons Filed matters </label>
                            </div>
                        </div>
                    </div>
                    <div class="row card card-primary card-outline">
                        <div class="col-sm-12">
                            <div class="form-check">            <input class="form-check-input" type="radio" name="is_efiled_pfield"  value="efiled" <?php if(!empty($formdata['is_efiled_pfield'])){ if($formdata['is_efiled_pfield'] == 'efiled') {echo 'checked';} }?> >
                                <label class="form-check-label">E-Filed matters</label>        </div>

                        </div>    <div class="col-sm-12">
                            <div class="form-check">            <input class="form-check-input" type="radio" name="is_efiled_pfield"  value="pfield" <?php if(!empty($formdata['is_efiled_pfield'])){ if($formdata['is_efiled_pfield'] == 'pfield') {echo 'checked';} }?> >
                                <label class="form-check-label">Physically Filed Matters</label>        </div>

                        </div>
                    </div>
                    <div class="row card card-primary card-outline">
                        <div class="col-sm-12">
                            <div class="form-check">            <input class="form-check-input" type="radio" name="reg_or_def" value="rd" <?php if(!empty($formdata['reg_or_def'])){ if($formdata['reg_or_def'] == 'rd') {echo 'checked';} }?> >
                                <label class="form-check-label">Registered Matters/Un-Registered Matters but Defects Removed</label>        </div>

                        </div>    <div class="col-sm-12">
                            <div class="form-check">            <input class="form-check-input" type="radio" name="reg_or_def" value="rd_dm" <?php if(!empty($formdata['reg_or_def'])){ if($formdata['reg_or_def'] == 'rd_dm') {echo 'checked';} }?> >
                                <label class="form-check-label">Defective Matters</label>        </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                        </div>
                        <div class="col-sm-6">

                            <button type="submit" name="diary_search" id="diary_search" class="btn btn-info btn-flat">Search</button>

                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </div>

        </div>
        <?= form_close();?>
        </div>
              <!-- /.diary -->
              <!-- caveat start -->
            <div class="<?php if(($uri->getSegment(3))=="caveat_search"){ echo 'active';}?> tab-pane" id="Caveat">
             <?php  $caveatattribute = array('class' => 'form-horizontal','name' => 'caveat_search', 'id' => 'caveat_search', 'autocomplete' => 'off');
                                            echo form_open(base_url('Filing/Report/caveat_search/'), $caveatattribute);             ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-primary">

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label for="From" class="col-sm-5 col-form-label">From</label>
                                                    <div class="col-sm-7">
                                                        <input type="date" class="form-control" id="from_date" name="from_date" placeholder="From Date" value="<?php if(!empty($formdata['from_date'])){ echo $formdata['from_date']; } ?>">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-sm-3">

                                                <div class="form-group row">
                                                    <label for="To" class="col-sm-5 col-form-label">To</label>
                                                    <div class="col-sm-7">
                                                        <input type="date" class="form-control" id="to_date" name="to_date" placeholder="TO Date" value="<?php if(!empty($formdata['to_date'])){ echo $formdata['to_date']; } ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label for="Dairy No." class="col-sm-5 col-form-label">Caveat No.</label>
                                                    <div class="col-sm-7">
                                                        <input type="number" class="form-control" id="caveat_no" name="caveat_no" placeholder="Enter Caveat No" value="<?php if(!empty($formdata['caveat_no'])){ echo $formdata['caveat_no']; } ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label for="Year" class="col-sm-5 col-form-label">Year</label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control select2" name="caveat_year" id="caveat_year"style="width: 100%;">
                                                        <?php echo !empty($formdata['caveat_year']) ? '<option value='.$formdata['caveat_year'].'>'.$formdata['caveat_year'].'</option>': '' ?> 
                                                            <option value="">Year</option>
                                                            <?php
                                                            $end_year = 47; $sel = '';
                                                            for ($i = 0; $i <= $end_year; $i++) {
                                                                $year = (int) date("Y") - $i;
                                                                echo '<option ' . $sel . ' value=' . $year. '>' . $year . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row ">

                                            <div class="col-sm-3">

                                                <div class="form-group row">
                                                    <label for="Party" class="col-sm-5 col-form-label">Party</label>
                                                    <div class="col-sm-7">
                                                        <select name="ddl_party_type" id="ddl_party_type" class="form-control " style="width: 100%;">
                                                            
                                                            <option value="" <?php if(!empty($formdata['ddl_party_type']) &&  $formdata['ddl_party_type'] == ''){  echo 'selected'; }?>>All</option>
                                                            <option value="P" <?php if(!empty($formdata['ddl_party_type']) && $formdata['ddl_party_type'] == 'P'){  echo 'selected'; }?>>Petitioner</option>
                                                            <option value="R" <?php if(!empty($formdata['ddl_party_type']) && $formdata['ddl_party_type'] == 'R'){  echo 'selected'; }?>>Respondent</option>
                                                            <option value="I" <?php if(!empty($formdata['ddl_party_type']) && $formdata['ddl_party_type']== 'I'){  echo 'selected'; }?>>Impleading</option>
                                                            <option value="N" <?php if(!empty($formdata['ddl_party_type']) && $formdata['ddl_party_type'] == 'N'){ echo 'selected'; }?>>Intervenor</option>

                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label for="Cause Title" class="col-sm-5 col-form-label">Cause Title</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="cause_title" id="cause_title" value="<?php if(!empty($formdata['cause_title'])){ echo $formdata['cause_title']; } ?>" placeholder="Cause Title">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label for="Case Type" class="col-sm-5 col-form-label">Case Type</label>
                                                    <div class="col-sm-7">
                                                        <select name="case_type_casecode" id="case_type_casecode" class="custom-select" style="width: 100%;">
                                                            <option value="">Select case type</option>
                                                            <?php
                                                            foreach ($casetype as $row) {?>
                                                                <!-- echo'<option value="' . sanitize(($row['casecode'])) . '">' . sanitize(strtoupper($row['casename'])) . '</option>'; -->
                                                                <option value=<?=sanitize(($row['casecode']))  ?> <?php if(!empty($formdata['case_type_casecode'])&& sanitize(($row['casecode']==$formdata['case_type_casecode']))){  echo 'selected'; }?>><?=sanitize(strtoupper($row['casename']))?></option>
                                                            <?php }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label for="Status" class="col-sm-5 col-form-label">Status</label>
                                                    <div class="col-sm-7">
                                                        <select name="ddl_status" id="ddl_status" class="form-control " style="width: 100%;">
                                                            <option value="" <?php if(!empty($formdata['ddl_status']) && ($formdata['ddl_status'] == '')) {echo 'selected';}?>>All</option>
                                                            <option value="P" <?php if(!empty($formdata['ddl_status']) && ($formdata['ddl_status'] == 'P')) {echo 'selected';}?>>Pending</option>
                                                            <option value="D" <?php if(!empty($formdata['ddl_status']) && ($formdata['ddl_status'] == 'D')) {echo 'selected';}?>>Disposed</option>
                                                        </select>   
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row ">

                                            <div class="col-sm-6">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox" name="caveat_greater_then_ninty_days" id="caveat_greater_then_ninty_days" <?php if(!empty($formdata['caveat_greater_then_ninty_days_no'])){ echo 'checked'; } ?>>
                                                    <label for="caveat_greater_then_ninty_days">Caveats Filed And Expired(>90 days old)</label>
                                                </div>

                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                            </div>
                                            <div class="col-sm-6">
                                        <span class="input-group-append">
                                        <button type="submit" name="caveat_search" id="caveat_search" class="btn btn-info btn-flat">Search</button>
                                        </span>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->

                            </div>

                        </div>
                        <!--/.col (right) -->
                        <?= form_close()?>

                    </div>
              <!-- /.caveat -->
            <div class="<?php if(($uri->getSegment(3))=="dak_search"){ echo 'active';}?> tab-pane" id="DAK">
             <?php      $dakattribute = array('class' => 'form-horizontal','name' => 'dak_search', 'id' => 'dak_search', 'autocomplete' => 'off');
                        echo form_open(base_url('Filing/Report/dak_search/'), $dakattribute); ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-primary">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="form-group row">
                                                    <label for="From" class="col-sm-3 col-form-label">From</label>
                                                    <div class="col-sm-9">
                                                        <input type="date" class="form-control" id="from_date" name="from_date" placeholder="From Date" value="<?php if(!empty($formdata['from_date'])){ echo $formdata['from_date']; } ?>" >
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-sm-2">

                                                <div class="form-group row">
                                                    <label for="To" class="col-sm-3 col-form-label">To</label>
                                                    <div class="col-sm-9">
                                                        <input type="date" class="form-control" id="to_date" name="to_date" placeholder="TO Date" value="<?php if(!empty($formdata['to_date'])){ echo $formdata['to_date']; } ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label for="Document No." class="col-sm-4 col-form-label">Document No.</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" id="document_no" name="document_no" placeholder="Document No" value="<?php if(!empty($formdata['document_no'])){ echo $formdata['document_no']; } ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group row">
                                                    <label for="Year" class="col-sm-3 col-form-label">Year</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control select2" name="doc_year" id="doc_year"style="width: 100%;">
                                                        <?php //echo !empty($formdata['doc_year']) ? '<option value='.$formdata['doc_year'].'>'.$formdata['doc_year'].'</option>': '' ?> 
                                                            <option value="">Year</option>
                                                            <?php echo !empty($formdata['doc_year']) ? '<option selected value='.$formdata['doc_year'].'>'.$formdata['doc_year'].'</option>': '' ?> 
                                                            <?php
                                                            $end_year = 47; $sel = '';
                                                            for ($i = 0; $i <= $end_year; $i++) {
                                                                $year = (int) date("Y") - $i;
                                                                echo '<option ' . $sel . ' value=' . $year. '>' . $year . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">


                                                <div class="form-group row">
                                                    <label for="Section" class="col-sm-3 col-form-label">Section</label>
                                                    <div class="col-sm-9">
                                                        <select name="section" id="section" class="custom-select rounded-0">
                                                            <option value="" title="Select">Select section</option>
                                                            <?php
                                                            foreach ($usersection as $row) { ?>
                                                                <option value="<?= sanitize(($row['id'])) ?>" <?php if(!empty($formdata["section"]) && $formdata["section"]==sanitize(($row['id']))) { ?> selected="selected" <?php } ?>> <?= sanitize(strtoupper($row['section_name'])) ?> </option>
                                                           <?php }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">


                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="summeryordetailed"  value="sod" 
                                                    <?php if(!empty($formdata['summeryordetailed']) && ($formdata['summeryordetailed'] == 'sod'))
                                                    {echo 'checked';} ?>>
                                                    <label class="form-check-label">Summary Report</label>
                                                </div>


                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="summeryordetailed" value="dr" <?php if(!empty($formdata['summeryordetailed']) && ($formdata['summeryordetailed'] == 'dr')) {echo 'checked';} ?>>
                                                    <label class="form-check-label">Detailed Report</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox" name="Case_Blocked" id="Case_Blocked" value="cb" <?php if(!empty($formdata['exclude']) && ($formdata['Case_Blocked'] == 'Case_Blocked')) {echo 'checked';} ?>>
                                                    <label for="Case_Blocked">Case Blocked & Receive Hide Doc</label >
                                                </div>
                                            </div>
                                            <!-- <div class="col-sm-3">
                                                <div class="icheck-alert d-inline">
                                                    <input type="checkbox" id="exclude" name="exclude" value="exclude" 
                                                    <?php if(!empty($formdata['exclude']) && ($formdata['exclude'] == 'exclude')) {echo 'checked';} ?>>
                                                    <label for="Exclude">Exclude Review/Contempt/Curative Petition</label>
                                                </div>
                                            </div> -->
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-5">
                                            </div>
                                            <div class="col-sm-7">
                                            <span class="input-group-append">
                                            <button type="submit" name="dak_search" id="dak_search" class="btn btn-info btn-flat">Search</button>
                                            </span>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->

                            </div>

                        </div>
                        <!--/.col (right) -->
                        <?= form_close()?>

                    </div>
                    <!-- /.DAK -->
        <div class="<?php if(($uri->getSegment(3))=="fil_trap_search"){ echo 'active';}?> tab-pane" id="Fil_Trap">
            <?php
            $fil_trap_searchattribute = array('class' => 'form-horizontal','name' => 'fil_trap_search', 'id' => 'fil_trap_search', 'autocomplete' => 'off');
            echo form_open(base_url('Filing/Report/fil_trap_search/'), $fil_trap_searchattribute);
            ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group row">
                                        <label for="From" class="col-sm-5 col-form-label">From</label>
                                        <div class="col-sm-7">
                                            <input type="date" class="form-control" id="from_date" name="from_date" value="<?php echo !empty($formdata['from_date']) ? $formdata['from_date'] : '' ?>" placeholder="From Date">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-3">

                                    <div class="form-group row">
                                        <label for="To" class="col-sm-5 col-form-label">To</label>
                                        <div class="col-sm-7">
                                            <input type="date" class="form-control" id="to_date" name="to_date"  value="<?php if(!empty($formdata['to_date'])) echo $formdata['to_date'] ?>" placeholder="TO Date">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group row">
                                        <label for="Dairy No." class="col-sm-5 col-form-label">Dairy No.</label>
                                        <div class="col-sm-7">
                                            <input type="number" class="form-control" id="diary_no" name="diary_no" placeholder="Enter Diary No"  value="<?php echo !empty($formdata['diary_no']) ? $formdata['diary_no'] : '' ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group row">
                                        <label for="Year" class="col-sm-5 col-form-label">Year</label>
                                        <div class="col-sm-7">
                                            <select class="form-control select2" name="diary_year" id="diary_year"style="width: 100%;">
                                            <?php echo !empty($formdata['diary_year']) ? '<option value='.$formdata['diary_year'].'>'.$formdata['diary_year'].'</option>': '' ?> 
                                                <option value="">Year</option>
                                                <?php
                                                $end_year = 47; $sel = '';
                                                for ($i = 0; $i <= $end_year; $i++) {
                                                    $year = (int) date("Y") - $i;
                                                    echo '<option ' . $sel . ' value=' . $year. '>' . $year . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row ">

                                <div class="col-sm-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="incompleteandcompletematter" value="im" <?php if(!empty($formdata['incompleteandcompletematter'])){ if($formdata['incompleteandcompletematter'] == 'im') {echo 'checked';} }?>>
                                        <label class="form-check-label">Incomplete Matter</label>
                                    </div>


                                </div>
                                <div class="col-sm-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="incompleteandcompletematter" value="cm" <?php if(!empty($formdata['incompleteandcompletematter'])){ if($formdata['incompleteandcompletematter'] == 'cm') {echo 'checked';} }?>>
                                        <label class="form-check-label">Completed Matter</label>
                                    </div>


                                </div>
                                <div class="col-sm-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reportview" value="cv" <?php if(!empty($formdata['reportview'])){ if($formdata['reportview'] == 'cv'){echo 'checked';}}?>>
                                        <label class="form-check-label">Complete View</label>
                                    </div>


                                </div>
                                <div class="col-sm-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reportview" value="sv" <?php if(!empty($formdata['reportview'])){ if($formdata['reportview'] == 'sv'){echo 'checked';}}?>>
                                        <label class="form-check-label">Summary View</label>
                                    </div>


                                </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                </div>
                                <div class="col-sm-6">

                        <span class="input-group-append">
                        <button type="submit" name="fil_trap_search" id="fil_trap_search" class="btn btn-info btn-flat">Search</button>
                        </span>
                                </div>
                            </div>


                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                </div>

            </div>
            <!--/.col (right) -->
            <?= form_close();?>
        </div>
            <!-- /.Fil_Trap -->
           <div class="<?php if(($uri->getSegment(3))=="case_search"){ echo 'active';}?> tab-pane" id="Case_Search">
            <?php
            $casesearchattribute = array('class' => 'form-horizontal','name' => 'case_search', 'id' => 'case_search', 'autocomplete' => 'off');
            echo form_open(base_url('Filing/Report/case_search/'), $casesearchattribute);
            ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                         <div class="card-body">
                               <div class="row">
                                   <div class="col-sm-3">
                                       <div class="form-group row">
                                           <label for="From" class="col-sm-5 col-form-label">From</label>
                                           <div class="col-sm-7">
                                               <input type="date" class="form-control" id="from_date" name="from_date" placeholder="From Date" value="<?php if(!empty($formdata['from_date'])){ echo $formdata['from_date']; } ?>">
                                           </div>
                                       </div>

                                   </div>
                                   <div class="col-sm-3">

                                       <div class="form-group row">
                                           <label for="To" class="col-sm-5 col-form-label">To</label>
                                           <div class="col-sm-7">
                                               <input type="date" class="form-control" id="to_date" name="to_date" placeholder="TO Date" value="<?php if(!empty($formdata['to_date'])){ echo $formdata['to_date']; } ?>">
                                           </div>
                                       </div>
                                   </div>
                                <div class="col-sm-3">
                                    <div class="form-group row">
                                        <label for="Case title Search" class="col-sm-5 col-form-label">Case title Search</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="case_title_search" name="case_title_search" placeholder="Case title Search" value="<?php if(!empty($formdata['case_title_search'])){ echo $formdata['case_title_search']; } ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group row">
                                        <label for="Dairy No." class="col-sm-5 col-form-label">Party Search</label>
                                        <div class="col-sm-7">

                                                <select name="ddl_party_type" id="ddl_party_type" class="form-control" style="width: 100%;">
                                                
                                               
                                                            <option value="">All</option>
                                                            <option value="P" <?php if(!empty($formdata['ddl_party_type']) && $formdata['ddl_party_type'] == 'P'){  echo 'selected'; }?>>Petitioner</option>
                                                            <option value="R" <?php if(!empty($formdata['ddl_party_type']) && $formdata['ddl_party_type'] == 'R'){  echo 'selected'; }?>>Respondent</option>
                                                            <option value="I" <?php if(!empty($formdata['ddl_party_type']) && $formdata['ddl_party_type'] == 'I'){  echo 'selected'; }?>>Impleading</option>
                                                            <option value="N" <?php if(!empty($formdata['ddl_party_type']) && $formdata['ddl_party_type'] == 'N'){  echo 'selected'; }?>>Intervenor</option>
                                                           
                                                </select>

                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="row ">
                                <div class="col-sm-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reportview" value="cv" <?php if(!empty($formdata['reportview'])){ if($formdata['reportview'] == 'sv'){echo 'checked';}}?>>
                                        <label class="form-check-label">Complete View</label>
                                    </div>


                                </div>
                                <div class="col-sm-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reportview" value="sv" <?php if(!empty($formdata['reportview'])){ if($formdata['reportview'] == 'sv'){echo 'checked';}}?>>
                                        <label class="form-check-label">Summary View</label>
                                    </div>


                                </div>
                                <div class="col-sm-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="search_against_order_details" value="saod_c" <?php if(!empty($formdata['search_against_order_details'])){ if($formdata['search_against_order_details'] == 'saod_c'){echo 'checked';}}?>>
                                        <label class="form-check-label">Caveat Search Against Order Details</label>
                                    </div>


                                </div>
                                <div class="col-sm-5">
                                    <div class="form-check">
                                        <input class="" type="radio" name="search_against_order_details" value="saod_d"  <?php if(!empty($formdata['search_against_order_details'])){ if($formdata['search_against_order_details'] == 'saod_d'){echo 'checked';}}?>>
                                        <label for="Dairy No." class="col-sm-5 col-form-label">Diary Search Against Order Details</label>
                                    </div>


                                </div>

                            </div>
                             <div class="row">
                                 <div class="col-sm-6">
                                 </div>
                                 <div class="col-sm-6">

                                <span class="input-group-append">
                                <button type="submit" name="case_search" id="case_search" class="btn btn-info btn-flat">Search</button>
                                </span>
                                 </div>
                             </div>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                </div>

            </div>
            <!--/.col (right) -->
            <?= form_close();?>
        </div>
             <!-- /.Case_Search -->
        <div class="<?php if(($uri->getSegment(3))=="refiling_search"){ echo 'active';}?> tab-pane" id="Refiling">
                    <?php
                    $refiling_searchattribute = array('class' => 'form-horizontal','name' => 'refiling_search', 'id' => 'refiling_search', 'autocomplete' => 'off');
                    echo form_open(base_url('Filing/Report/refiling_search/'), $refiling_searchattribute);
                    ?>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="From" class="col-sm-5 col-form-label">From</label>
                                                <div class="col-sm-7">
                                                    <input type="date" class="form-control" id="from_date" name="from_date" placeholder="From Date"  value="<?php if(!empty($formdata['from_date'])){ echo $formdata['from_date']; } ?>">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-sm-3">

                                            <div class="form-group row">
                                                <label for="To" class="col-sm-5 col-form-label">To</label>
                                                <div class="col-sm-7">
                                                    <input type="date" class="form-control" id="to_date" name="to_date" placeholder="TO Date" value="<?php if(!empty($formdata['to_date'])){ echo $formdata['to_date']; } ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="Dairy No." class="col-sm-5 col-form-label">Dairy No.</label>
                                                <div class="col-sm-7">
                                                    <input type="number" class="form-control" id="diary_no" name="diary_no" placeholder="Enter Diary No" value="<?php if(!empty($formdata['diary_no'])){ echo $formdata['diary_no']; } ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group row">
                                                <label for="Year" class="col-sm-5 col-form-label">Year</label>
                                                <div class="col-sm-7">
                                                    <select class="form-control select2" name="diary_year" id="diary_year"style="width: 100%;">
                                                    <?php echo !empty($formdata['diary_year']) ? '<option value='.$formdata['diary_year'].'>'.$formdata['diary_year'].'</option>': '' ?> 
                                                        <option value="">Year</option>
                                                        <?php
                                                        $end_year = 47; $sel = '';
                                                        for ($i = 0; $i <= $end_year; $i++) {
                                                            $year = (int) date("Y") - $i;
                                                            echo '<option ' . $sel . ' value=' . $year. '>' . $year . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row ">

                                        <div class="col-sm-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="reportview" value="cv" <?php if(!empty($formdata['reportview'])){ if($formdata['reportview'] == 'cv'){echo 'checked';}}?>>
                                                <label class="form-check-label">Complete View</label>
                                            </div>


                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="reportview" value="sv" <?php if(!empty($formdata['reportview'])){ if($formdata['reportview'] == 'sv'){echo 'checked';}}?>>
                                                <label class="form-check-label">Summary View</label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                        </div>
                                        <div class="col-sm-6">
                                        <span class="input-group-append">
                                        <button type="submit" name="refiling_search" id="refiling_search" class="btn btn-info btn-flat">Search</button>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>

                    </div>
                    <!--/.col (right) -->
                    <?= form_close();?>
                </div>
                <!-- /.Refiling -->
            </div>
    <div class="card">
    <div class="card-body" >
    <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
        <?php if(!empty($ReportsoffileTrap)):?>
            <table  id="ReportFileTrap" class="table table-bordered table-striped">
            <thead><tr><th>SNo.</th><th>Diary No.</th>
                <th>Dispatch By</th>
                <th>Dispatch On</th><th>Remarks</th>
                <th>Dispatch To</th><th>Receive By</th><th>Receive On</th><th>Completed On</th>
            </tr></thead><tbody>
            <?php $sno = 1; foreach($ReportsoffileTrap as $row):?>
                <tr><th><?php echo $sno++; ?></th>
                    <td><?php echo substr($row->diary_no,0,-4).'/'.substr($row->diary_no,-4); ?></td>
                    <td><?php echo $row->d_by_name; ?></td>
                    <td><?php echo ($row->disp_dt) ? date('d-m-Y h:i:s A', strtotime($row->disp_dt)) :''; ?></td>
                    <td><?php echo $row->remarks; ?></td>
                    <td><?php echo $row->d_to_name;?></td>
                    <td><?php echo $row->r_by_name; ?></td>
                    <td><?php echo ($row->rece_dt) ? date('d-m-Y h:i:s A', strtotime($row->rece_dt)) : ''; ?></td>
                    <td><?php echo ($row->comp_dt)? date('d-m-Y h:i:s A', strtotime($row->comp_dt)) : '';
                        if($row->other!=0) echo '<br> '.$row->o_name; ?></td>
                </tr>
            <?php endforeach; ?>
       </tbody>
     </table>
     <?php endif; ?>
    <!-- end of fileTrap -->

    <?php  if(!empty($Reportsrefiling)):?>
    <table  id="ReportRefiling" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>SNo. </th>
        <th>DiaryNo/Diary Date </th>
        <th>Registration No. & Date</th>
        <th>Cause Title </th>
        <th>Petitioner Advocate </th>
        <th>Diary User</th>
        <th>State/Lower Court Information</th>
        <th>Total Pet.</th>
        <th>Total Res.</th>
        <!-- <th>Section</th>     -->
        <th>E-mail ID & Mobile No.</th>
        <!-- <th>Status</th>  -->
    </tr>
    </thead><tbody>
    <?php
    $sno = 1;
    foreach($Reportsrefiling as $row):?>
        <tr>
            <td><?= $sno++ ?></td>
            <td><?= $row->diary_no ?>/<?= $row->diary_year ?><br><?php echo date('d-m-Y',strtotime($row->diary_date));?></td>
            <td><?= $row->fil_no ?><br><?php ($row->active_fil_dt) ? date('d-m-Y',strtotime($row->active_fil_dt)) : ''?></td>
            <td><?= $row->pet_name ?> Vs. <?= $row->res_name ?></td>
            <td><?= $row->pet_adv_id ?></td>
            <td><?= $row->diary_user_id ?></td>            <td><?= $row->ref_agency_state_id?> # <?= $row->ref_agency_code_id?></td>
            <td><?= $row->pno?></td>            <td><?= $row->rno?></td>
            <!-- <td><?= $row->email?><br><?= $row->mobile?></td> -->
            <td><?= $row->email?><br><?= $row->mobile?></td>
            <!-- <td></td>         -->
        </tr>
    <?php endforeach; ?>
    </tbody>
    </tfoot>
    </table>
    <?php endif; ?>
    <!-- end of refiling seach -->

    <?php if(!empty($Reportsofdak)):?>
    <table  id="ReportDak" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>SNo. </th>
        <th>Diary No </th>
        <th>Doc Year</th>
        <th>Remark </th>
        <th>Filed By </th>
        <th>Doc Description</th>
        <th>Advocate</th>
        <th>Entry User</th>
        <!-- <th>Section</th>     -->
        <th>Active File Number</th>

    </tr>
    </thead><tbody>
    <?php
    $sno = 1;
    foreach($Reportsofdak as $row):?>
        <tr>
            <td><?= $sno++ ?></td>
            <td><?= $row->diary_no.'/'.$row->diary_year?></td>
            <td><?= $row->docyear ?></td>
            <td><?= $row->remark ?></td>
            <td><?= $row->filedby ?></td>
            <td><?= $row->docdesc .'<br>'. $row->short_description ?></td>
            <td><?= $row->advname?></td>
            <td><?= $row->entryuser?></td>
            <td><?= $row->active_fil_no?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    </tfoot>
    </table>
    <?php endif; ?>

    <!-- end of dak -->

    <?php if(!empty($Reportsofcaveat)):?>
    <table  id="ReportCaveat" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th style="text-align: center;">Sr.No.</th>
        <th width="13%" style="text-align: left;">Caveat No#Caveat Date</th>
        <th>Lower Court Details</th>
        <th width="8%">Diary No.</th>
        <th width="30%">Cause Title</th>
        <!-- <th width="10%">Caveator Advocate </th> -->
        <th> DAYS </th>
        <th width="10%">Petitioner Advocate </th>
        <th>Court Fee#Total Court Fee</th>
        <th width="7%">Diary User</th>
        <!--<th width="10%">State/Lower Court Information</th>-->
    </tr>
    </thead>
    <tbody>
    <?php
    $sno = 1;
    foreach($Reportsofcaveat as $row):?>
        <tr>
            <td><?= $sno++ ?></td>
            <td><?php //echo $row->caveat_no1 ?><?php echo $row->caveat_year ?>#<?php echo date('d-m-Y',strtotime($row->caveat_date));?>
                <?php
                if($row->no_of_days > 90)
                {?> <font style='text-align: center;font-size: 14px;color: black'> STATUS:</font><span style="color:red"><?php echo "Expired";?></span> <?php
                }
                else
                { ?>
                    <font style='text-align: center;font-size: 14px;color: black'> STATUS:</font><span style="color:green"><?php echo "Active";?></span> <?php
                }
                ?>
            </td>
            <td><?= '<b>'.$row->ref_agency_state_id .'<br>'.$row->ref_agency_code_id.'</b>'?></td>
            <td><?=$row->caveat_year ?></td>

            <td><?= $row->pet_name ?> Vs. <?= $row->res_name ?></td>
            <td><?= $row->no_of_days?></td>
            <td><?= $row->pet_adv_id?></td>
            <td><?= $row->court_fee ?> # <?= $row->total_court_fee ?></td>
            <td><?= $row->diary_user_id ?></td>

        </tr>
    <?php endforeach; ?>
    </tbody>
     </table>
    <?php endif; ?>
    <!-- end caveat -->

    <?php  if(!empty($ReportsofCase)):?>

    <table  id="ReportCase" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th> SNo. </th>
        <th> DiaryNo / Diary Date </th>
        <th> Registration No. & Date</th>
        <th> Cause Title </th>
        <th> Petitioner Advocate </th>
        <th>Diary User</th>
        <th>State/Lower Court Information</th>
        <th>Total Pet.</th>
        <th>Total Res.</th>
        <!-- <th>Section</th>     -->
        <th>E-mail ID & Mobile No.</th>
        <!-- <th>Status</th>  -->
    </tr>
    </thead><tbody>
    <?php
    $sno = 1;
    foreach($ReportsofCase as $row):?>
        <tr>
            <td><?= $sno++ ?></td>
            <td><?= $row->diary_no.'/'.$row->diary_year ?><br><?php echo date('d-m-Y',strtotime($row->diary_date));?></td>
            <td><?= $row->fil_no ?><br><?php ($row->active_fil_dt) ? date('d-m-Y',strtotime($row->active_fil_dt)) : ''?></td>
            <td><?= $row->pet_name ?> Vs. <?= $row->res_name ?></td>
            <td><?= $row->pet_adv_id ?></td>
            <td><?= $row->diary_user_id ?></td>            <td><?= $row->ref_agency_state_id?> # <?= $row->ref_agency_code_id?></td>
            <td><?= $row->pno?></td>            <td><?= $row->rno?></td>
            <!-- <td><?= $row->email?><br><?= $row->mobile?></td> -->
            <td><?= $row->email?><br><?= $row->mobile?></td>
            <!-- <td></td>         -->
        </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
    <?php endif; ?>
    <!-- end report of case search -->


    <?php  if(!empty($ReportsofCause)):?>

    <table  id="ReportCause" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>SNo. </th>
        <th>DiaryNo / Diary Date </th>
        <th>Registration No. & Date</th>
        <th>Cause Title </th>
        <th>Petitioner Advocate </th>
        <th>Diary User</th>
        <th>State/Lower Court Information</th>
        <th>Total Pet.</th>
        <th>Total Res.</th>
        <!-- <th>Section</th>     -->
        <th>E-mail ID & Mobile No.</th>
        <th>Status</th>
    </tr>
    </thead><tbody>
    <?php
    $sno = 1;
    foreach($ReportsofCause as $row):?>
        <tr>
            <td><?= $sno++ ?></td>
            <td><?= $row->diary_no.'/'.$row->diary_year?><br><?php echo date('d-m-Y',strtotime($row->diary_date));?></td>
            <td><?= $row->fil_no ?><br><?php ($row->active_fil_dt) ? date('d-m-Y',strtotime($row->active_fil_dt)) : ''?></td>
            <td><?= $row->pet_name ?> Vs. <?= $row->res_name ?></td>
            <td><?= $row->pet_adv_id ?></td>
            <td><?= $row->diary_user_id ?></td>            <td><?= $row->ref_agency_state_id?> # <?= $row->ref_agency_code_id?></td>
            <td><?= $row->pno?></td>            <td><?= $row->rno?></td>
            <!-- <td><?= $row->email?><br><?= $row->mobile?></td> -->
            <td><?= $row->email?><br><?= $row->mobile?></td>
            <td><?= $row->status?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
    <?php endif; ?>

     </div>
    <!-- /.card-body -->
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
        
        $('#diary_search_click').click(function(){
            $('#query_builder_wrapper, #ReportFileTrap, #ReportDak, #ReportRefeling, #ReportCase, #ReportCaveat').hide();
        })
        $('#caveat_search_click').click(function(){
            $('#query_builder_wrapper, #ReportCause, #ReportFileTrap, #ReportDak, #ReportRefeling, #ReportCase').hide();
        })
        $('#dak_search_click').click(function(){
            $('#query_builder_wrapper, #ReportCause, #ReportFileTrap, #ReportCaveat, #ReportCase, #ReportRefeling').hide();
        })
        $('#filetrap_search_click').click(function(){
            $('#query_builder_wrapper,  #ReportDak, #ReportCause, #ReportCaveat, #ReportCase, #ReportRefeling').hide();
        })
        $('#case_search_click').click(function(){
            $('#query_builder_wrapper, #ReportCause, #ReportFileTrap,  #ReportDak,  #ReportCaveat, #ReportRefeling').hide();
        })
        $('#refiling_search_click').click(function(){
            $('#query_builder_wrapper, #ReportCause, #ReportFileTrap,  #ReportDak, #ReportCaveat , #ReportCase').hide();
        })

        $(function () {

            //Date picker
            $('#date_from').datetimepicker({
                format: 'L'
            });
            $('#date_to').datetimepicker({
                format: 'L'
            });



        })


        $(function () {
            $("#ReportCause,#ReportCaveat,#ReportFileTrap,#ReportDak,#ReportRefiling,#ReportCase").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf"],
                "bProcessing": true,
                //"bServerSide": true,
            }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true,
            });
        });


    </script>



 <?=view('sci_main_footer') ?>