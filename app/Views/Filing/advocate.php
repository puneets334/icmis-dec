<?= view('header'); ?>

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

    .basic_heading {
        text-align: center;
        color: #31B0D5
    }

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
</style>
<link href="<?php echo base_url(); ?>/css/jquery-ui.css" rel="stylesheet">
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Filing >> Advocate</h3>
                            </div>

                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>


                    <?= view('Filing/filing_breadcrumb'); ?>


                    <?php
                    $filing_details = session()->get('filing_details');
                    $user_details = session()->get('login');
                    ?>
                    <!-- /.card-header -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff;">
                                    <ul class="nav nav-pills inner-comn-tabs">
                                        <li class="nav-item"><a id="advocate" class="nav-link active" onclick="advocateBtn()" href="#advocate_tab_panel" data-toggle="tab">Advocate</a></li>
                                        <li class="nav-item"><a id="search" class="nav-link" onclick="searchBtn()" href="#search_advocate_tab_panel" data-toggle="tab">Search</a></li>
                                        <li class="nav-item"><a id="add_caveator" class="nav-link" onclick="addCaveator()" href="#add_caveator_writ" data-toggle="tab">Add Caveator In Writ</a></li>
                                        <li class="nav-item"><a id="add_caveator" class="nav-link" onclick="updateAdvocate()" href="#update_advocate" data-toggle="tab">Update</a></li>
                                    </ul>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">

                                        <div class="active tab-pane" id="advocate_tab_panel">
                                            <h4 class="basic_heading"> Addition of Additional Advocate </h4><br>
                                            <?php
                                            if (!empty($get_party_type)) {
                                                $method = 'updateAdvocate';
                                                $attr = 'disabled';
                                            } else {
                                                $method = 'addAdvocate';
                                                $attr = '';
                                            }
                                            $attribute = array('class' => 'form-horizontal', 'name' => '', 'id' => '', 'autocomplete' => 'off');
                                            echo form_open('Filing/Advocate/' . $method, $attribute);

                                            ?>

                                            <?php if ($main_row['c_status'] == 'D') { ?>
                                                <span style="color:red;">
                                                    <center><b>!!!The Case is Disposed!!!</b></center>
                                                </span>
                                            <?php } elseif ($main_row['c_status'] == 'P') { ?>

                                                <div class="form-group row ">

                                                    <label for="inputEmail3" class="col-sm-2 py-0 col-form-label"> Party Type<span style="color: red">*</span>: </label>
                                                    <div class="col-sm-9">

                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input party" type="radio" id="radio_selected_court1" required name="get_party" value="P" maxlength="2" <?= $attr; ?> <?php echo ($get_pet_res == 'P') ? 'checked' : '';  ?>>
                                                            <label for="radio_selected_court1" class="custom-control-label">Petitioner</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input party" type="radio" id="radio_selected_court2" required name="get_party" value="R" maxlength="2" <?= $attr; ?> <?php echo ($get_pet_res == 'R') ? 'checked' : ''; ?>>
                                                            <label for="radio_selected_court2" class="custom-control-label">Respondent</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input party" type="radio" id="radio_selected_court3" required name="get_party" value="I" maxlength="2" <?= $attr; ?> <?php echo ($get_pet_res == 'I') ? 'checked' : ''; ?>>
                                                            <label for="radio_selected_court3" class="custom-control-label">Impleader</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input party" type="radio" id="radio_selected_court4" required name="get_party" value="N" maxlength="2" <?= $attr; ?> <?php echo ($get_pet_res == 'N') ? 'checked' : ''; ?>>
                                                            <label for="radio_selected_court4" class="custom-control-label">Intervenor</label>
                                                        </div>

                                                    </div>
                                                </div>


                                                <div class="row ">

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 col-form-label">Party Name</label>
                                                            <div class="col-sm-8">
                                                                <select name="party_name[]" id="part_name" multiple class="custom-select rounded-0" <?php echo !empty($get_party_type) ? 'disabled' : ''; ?> required>
                                                                    <option value="">Select</option>
                                                                    <?php foreach ($get_party_edit as $get_party_val): ?>
                                                                        <option value="<?php echo $get_party_val['sr_no'] . '/' . $get_party_val['sr_no_show']; ?>" selected><?php echo $get_party_val['sr_no_show'] . '-' . $get_party_val['partyname']; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 col-form-label">Category</label>
                                                            <div class="col-sm-8">
                                                                <select name="category" class="custom-select rounded-0 category" <?php echo !empty($get_party_type) ? 'disabled' : ''; ?>>
                                                                    <option value="M">Main</option>
                                                                    <option value="A" selected>Additional</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 col-form-label">Type</label>
                                                            <div class="col-sm-8">
                                                                <?php
                                                                $string = $get_adv_selected_data;
                                                                $str = (!empty($string)) ? $string : '';
                                                                if (stripos($str, "[SURETY]") !== false) {
                                                                    $surety = "selected";
                                                                } else {
                                                                    $surety = "";
                                                                }

                                                                if (stripos($str, "[INT]") !== false) {
                                                                    $int = "selected";
                                                                } else {
                                                                    $int = "";
                                                                }

                                                                if (stripos($str, "[LR/S]") !== false) {
                                                                    $lrs = "selected";
                                                                } else {
                                                                    $lrs = "";
                                                                }

                                                                if (stripos($str, "[DRW]") !== false) {
                                                                    $drw = "selected";
                                                                } else {
                                                                    $drw = "";
                                                                }

                                                                if (stripos($str, "[SCLSC]") !== false) {
                                                                    $sclsc = "selected";
                                                                } else {
                                                                    $sclsc = "";
                                                                }
                                                                ?>
                                                                <select name="type" class="custom-select rounded-0 type">
                                                                    <option value="N">None</option>
                                                                    <option value="SURETY" <?= $surety ?>>SURETY</option>
                                                                    <option value="INT" <?= $int ?>>INTERVENOR</option>
                                                                    <option value="LR/S" <?= $lrs ?>>LR/S</option>
                                                                    <option value="DRW" <?= $drw ?>>DRAWNBY</option>
                                                                    <option value="SCLSC" <?= $sclsc ?>>SCLSC</option>
                                                                    <?php
                                                                    if (!empty($get_party_type)) {
                                                                        if ($get_pet_res == 'R') {

                                                                            if (stripos($str, "[OBJ]") !== false) {
                                                                                $obj = "selected";
                                                                            } else {
                                                                                $obj = "";
                                                                            }

                                                                            if (stripos($str, "[IMPL]") !== false) {
                                                                                $impl = "selected";
                                                                            } else {
                                                                                $impl = "";
                                                                            }

                                                                            if (stripos($str, "[COMP]") !== false) {
                                                                                $comp = "selected";
                                                                            } else {
                                                                                $comp = "";
                                                                            }
                                                                    ?>
                                                                            <option value="OBJ" <?= $obj ?>>OBJECTOR</option>
                                                                            <option value="IMPL" <?= $impl ?>>IMPLEADER</option>
                                                                            <option value="COMP" <?= $comp ?>>COMPLAINANT</option>
                                                                    <?php }
                                                                    } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 col-form-label">Advocate Details</label>
                                                            <div class="col-sm-8">
                                                                <select name="adv_detail" id="adv_detail" class="custom-select rounded-0 adv_detail">
                                                                    <option value="A" <?php echo ($aor_state == 'A') ? 'selected' : ''; ?>>AOR</option>
                                                                    <option value="S" <?php echo ($aor_state == 'S') ? 'selected' : ''; ?>>State</option>
                                                                    <option value="AC" <?php echo ($aor_state == 'AC') ? 'selected' : ''; ?>>NON-AOR</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4" id="show_state" style="<?php echo ($aor_state == 'S') ? '' : 'display:none'; ?>">
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 col-form-label">State <span style="color: red">*</span></label>
                                                            <div class="col-sm-8">
                                                                <select name="state" id="state_id" class="custom-select rounded-0">
                                                                    <option value="">--Select--</option>
                                                                    <?php foreach ($state_list as $state_value): ?>
                                                                        <option value="<?= !empty($state_value['cmis_state_id']) ? $state_value['cmis_state_id'] : '' ?>"><?= $state_value['state_name'] ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label id="title" class="col-sm-4 col-form-label">AOR Code</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" name="aor_code" id="aor_code" maxlength="10" class="form-control bar_detail numbersonly_" value="<?php if ($aor_state == 'S') {
                                                                                                                                                echo $enroll_no;
                                                                                                                                            } else {
                                                                                                                                                echo $bar_id;
                                                                                                                                            }; ?>" placeholder="Enter Number" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4" id="show_state_code" style="<?php echo ($aor_state == 'S') ? '' : 'display:none'; ?>">
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 col-form-label">Advocate Year</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" maxlength="4"   name="adv_year" class="form-control bar_detail_adv numbersonly" value="<?php echo $enroll_date; ?>" placeholder="Enter Number">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 col-form-label">Advocate Name <span style="color: red">*</span></label>
                                                            <div class="col-sm-8">
                                                                <input type="text" id="adv_name" name="adv_name" value="<?php echo $name; ?>" class="form-control" readonly>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php
                                                    $mobile_attr = 'none';
                                                    $email_attr = 'none';

                                                    if ($get_party_type) {
                                                        if ($get_sr_no_show == 0) {

                                                            if ($bar_id == 1034 || $bar_id == 1372 || $bar_id == 800 || $bar_id == 616 || $bar_id == 892) {
                                                                $mobile_attr = 'block';
                                                                $email_attr = 'block';
                                                            }
                                                        } elseif ($bar_id == 799 || $bar_id == 1034 || $bar_id == 1372 || $bar_id == 800 || $bar_id == 616 || $bar_id == 868) {
                                                            $mobile_attr = 'block';
                                                            $email_attr = 'block';
                                                        }
                                                    }
                                                    ?>

                                                    <div class="col-md-4" id="inperson_mob_div" style="display:<?= $mobile_attr ?>;">
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 col-form-label">Mobile No.</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" maxlength="10" size="10" id="inperson_mob" name="inperson_mob" value="<?= $inperson_mobile ?>" placeholder="Mobile no." class="form-control" onkeypress="return onlynumbers(event,this.id)">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4" id="inperson_email_div" style="display:<?= $email_attr ?>;">
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 col-form-label">Email Id</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" id="inperson_email" name="inperson_email" value="<?= $inperson_email ?>" placeholder="Email id" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 col-form-label">If [AG]</label>
                                                            <div class="col-sm-8">
                                                                <?php
                                                                if (stripos($str, "[AG]") !== false) {
                                                                    $ag = "selected";
                                                                } else {
                                                                    $ag = "";
                                                                }
                                                                ?>
                                                                <select name="if_ag" class="custom-select rounded-0">
                                                                    <option value="N">NO</option>
                                                                    <option value="AG" <?= $ag ?>>ATTORNY GENERAL</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 col-form-label">STATE ADV[Pri/Gov]</label>
                                                            <div class="col-sm-8">
                                                                <?php
                                                                if (stripos($str, "[Pr]") !== false) {
                                                                    $pr = "selected";
                                                                } else {
                                                                    $pr = "";
                                                                }

                                                                if (stripos($str, "[Gr]") !== false) {
                                                                    $gr = "selected";
                                                                } else {
                                                                    $gr = "";
                                                                }
                                                                ?>
                                                                <select name="state_adv" class="custom-select rounded-0">
                                                                    <option value="N">NO</option>
                                                                    <option value="P" <?= $pr ?>>Private</option>
                                                                    <option value="G" <?= $gr ?>>Government</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="get_party_type" value="<?php echo !empty($get_party_type) ? $get_party_type : ''; ?>">

                                                    <div class="col-md-12">
                                                        <center>
                                                            <?php
                                                            if (!empty($get_party_type)) {
                                                                $btn_lable = 'Update';
                                                            } else {
                                                                $btn_lable = 'Save';
                                                            }
                                                            ?>
                                                            <input type="submit" name="sub" class="btn btn-primary" onclick="return checkAdvocateName()" value="<?= $btn_lable ?>">
                                                            <?php if (!empty($get_party_type)): ?>
                                                                <a href="<?php echo base_url('Filing/Advocate'); ?>"><button type="button" class="btn btn-info">Cancel</button></a>
                                                            <?php endif; ?>
                                                        </center>
                                                    </div>

                                                </div>

                                            <?php } else { ?>
                                                <span style="color:red;">
                                                    <center><b>Record Not Found!!!</b></center>
                                                </span>
                                            <?php } ?>

                                        </div>
                                        <!-- /.advocate_tab_panel -->

                                        <div class="tab-pane" id="search_advocate_tab_panel">
                                            <h4 class="basic_heading"> Advocate Search </h4>

                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group row cus-mx-none">
                                                        <label class="col-sm-12 col-form-label px-0">Search by Name</label>
                                                        <div class="col-sm-12 px-0">
                                                            <input type="text" id="adv_search_name" class="form-control" placeholder="Enter Advocate Name">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row cus-mx-none">
                                                        <label class="col-sm-12  px-0 col-form-label">Search by AOR Code</label>
                                                        <div class="col-sm-12 px-0">
                                                            <input type="number" id="adv_search_by_aor" class="form-control" placeholder="Enter AOR Code">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row mt-3 cus-mx-none">
                                                        <div class="col-md-12 px-0">
                                                            <div id="sr_adv_div_not_found">
                                                                <center><b>Advocate not found</b></center>
                                                            </div>
                                                            <div id="sr_adv_div" style="display:grid;justify-content: flex-start;">
                                                                <div class="mb-1" style="font-family: Font Awesome 5 Free;">
                                                                    <span class="detail-label">Name:</span>
                                                                    <span id="adv_name_sear"></span>
                                                                </div>
                                                                <div class="mb-1" style="font-family: Font Awesome 5 Free;">
                                                                    <span class="detail-label">AOR/NAOR:</span>
                                                                    <span id="adv_aor_sear"></span>
                                                                </div>
                                                                <div class="mb-1" id="aor_code_hide_show" style="font-family: Font Awesome 5 Free;">
                                                                    <span class="detail-label">AOR Code:</span>
                                                                    <span id="adv_aor_code_sear"></span>
                                                                </div>
                                                                <div class="mb-1" style="font-family: Font Awesome 5 Free;">
                                                                    <span class="detail-label">Mobile:</span>
                                                                    <span id="adv_mobile_sear"></span>
                                                                </div>
                                                                <div class="mb-1" style="font-family: Font Awesome 5 Free;">
                                                                    <span class="detail-label">Email:</span>
                                                                    <span id="adv_email_sear"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <!-- /.advocate_tab_panel -->



                                        <div id="show_view">
                                            <hr><br>
                                            <div class="">
                                                <h4 class="basic_heading"> View </h4><br>

                                                <div class="form-group row ">

                                                    <label for="inputEmail3" class="col-sm-2 py-0 col-form-label"> Party Type<span style="color: red">*</span> : </label>
                                                    <div class="col-sm-9">

                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input partyView" type="radio" id="radio_selected_court5" attrlbl="Petitioner" name="view_party" value="P" maxlength="2" <?php echo ($get_pet_res == 'P') ? 'checked' : '';  ?> checked>
                                                            <label for="radio_selected_court5" class="custom-control-label">Petitioner</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input partyView" type="radio" id="radio_selected_court6" attrlbl="Respondent" name="view_party" value="R" maxlength="2" <?php echo ($get_pet_res == 'R') ? 'checked' : '';  ?>>
                                                            <label for="radio_selected_court6" class="custom-control-label">Respondent</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input partyView" type="radio" id="radio_selected_court7" attrlbl="Impleader" name="view_party" value="I" maxlength="2" <?php echo ($get_pet_res == 'I') ? 'checked' : '';  ?>>
                                                            <label for="radio_selected_court7" class="custom-control-label">Impleader</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input partyView" type="radio" id="radio_selected_court8" attrlbl="Intervenor" name="view_party" value="N" maxlength="2" <?php echo ($get_pet_res == 'N') ? 'checked' : '';  ?>>
                                                            <label for="radio_selected_court8" class="custom-control-label">Intervenor</label>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h4 class="basic_heading" id="view_id">Petitioner</h4>
                                                        <div class="showData">
                                                            <table id="example1" class="table table-striped table-bordered custom-table" style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>S.No.</th>
                                                                        <th>Name</th>
                                                                        <th>Advocate Name</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                // pr($party);
                                                                    if (!empty($party)):
                                                                        foreach ($party as $party_val): ?>
                                                                            <tr>
                                                                        <td><?php echo "[P-".$party_val['sr_no_show']."]";  
                                                                            ?>
                                                                        <td><?php echo $party_val['partyname'];
                                                                            ?></td>
                                                                        <td></td>
                                                                    </tr> 
                                                                    <?php endforeach;
                                                                    endif; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="tab-pane" id="add_caveator_writ">
                                            <h4 class="basic_heading"> Add Caveator In Writ </h4><br><br>


                                            <div class="row">
                                                <div class="col-sm-7" id="caseInfo">
                                                    <div class="form-group row">
                                                        <h5 for="information" style="color:red">Diary Information:</h5>&nbsp;&nbsp;
                                                        <div class="col-sm-9">
                                                            <span> Petitioner: </span><span style='text-align: center;color: blue' id="pet"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-5" id="caseInfo">
                                                    <div class="form-group row">
                                                        <div class="col-sm-9">
                                                            <span> Respondent: </span><span style='text-align: center;color: blue' id="res"></span>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>

                                            <div class="row" id="select_remarks_div">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label">Select Aor Code:</label>
                                                        <div class="col-sm-7">
                                                            <select class="form-control" id="aorcode">
                                                                <option value="">select</option>
                                                                <!--                                                                    --><?php
                                                                                                                                            //
                                                                                                                                            //                                                                    $sql="select aor_code, bar_id,name from bar where if_aor='Y' and isdead='N'";
                                                                                                                                            //
                                                                                                                                            //                                                                    $rs=mysql_query($sql);
                                                                                                                                            //                                                                    while($rw=mysql_fetch_array($rs))
                                                                                                                                            //                                                                    {
                                                                                                                                            //                                                                        
                                                                                                                                            ?>
                                                                <!---->
                                                                <!--                                                                        <option value="--><?php //echo $rw[bar_id] 
                                                                                                                                                                ?><!--">--><?php //echo $rw[aor_code]."-".$rw[name];  
                                                                                                                                                                                                    ?><!--</option>-->
                                                                <!--                                                                        --><?php
                                                                                                                                                //                                                                    }
                                                                                                                                                //                                                                    
                                                                                                                                                ?>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label">Enter Remarks :</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="remarks" name="remarks" placeholder="Remarks" value="">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <br><br>

                                            <center>
                                                <div class="col-md-2" id="btn_div">
                                                    <button type="button" id="button" class="btn btn-primary" onClick="insert_advocate()">Add Advocate</button>

                                                </div>
                                            </center>
                                            <br><br>
                                            <center>
                                                <div id="message_for_disposalcase"> </div>
                                            </center>

                                        </div>

                                        <div class="tab-pane" id="update_advocate">
                                            <h4 class="basic_heading"> Modification of Additional Advocate </h4>
                                            <div id="result12" style="text-align: center;color:red;font-size: larger"></div>
                                            <br><br>
                                            <div id="result1" style="text-align: center;color:green;font-size: larger"></div>

                                        </div>
                                        <!--                                            <div class="form-group" id="add_caveator_writ"></div>-->
                                        <!--                                            **************************** CODE ADDED BY P.S TO ADD CAVEATOR IN WRIT MODULE IN ADVOCATE MENU ENDS HERE ***************************-->
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

<script src="<?php echo base_url(); ?>/jquery/jquery-1.9.1.js"></script>
<script src="<?php echo base_url(); ?>/js/jquery-ui.js"></script>

<script>
    // **************************** CODE ADDED BY P.S TO ADD CAVEATOR IN WRIT MODULE IN ADVOCATE MENU ***************************
    function addCaveator() {
        $('#show_view').hide();
        $('#advocate_tab_panel').hide();
        $('#search_advocate_tab_panel').hide();
        $('#add_caveator_writ').show();
        $('#update_advocate').hide();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
            },
            url: "<?php echo base_url('Filing/Advocate/show_add_caveator'); ?>",
            success: function(data) {
                // alert(data);
                updateCSRFToken();
                var info = JSON.parse(data);
                // alert(info.pet_name);
                $("#pet").html(info.pet_name);
                $("#res").html(info.res_name);
                if (info.status == 'P') {
                    var option = '';
                    option += "<option value=''>" + "Select" + "</option>";
                    for (var i in info.aor) {
                        console.log(info.aor[i].bar_id + ' ' + info.aor[i].name);
                        option += "<option value=" + info.aor[i].bar_id + ">" + info.aor[i].aor_code + "-" + info.aor[i].name + "</option>";
                    }
                    $("#aorcode").html(option);

                } else {
                    $("#select_remarks_div").hide();
                    $("#btn_div").hide();
                    $("#message_for_disposalcase").html("<span style='color:Red;'> The Case Is Disposed, Cannot Add Caveat In Writ</span>");


                }
            },
            error: function(data) {
                alert(data);
                updateCSRFToken();
            }
        });


    }

    function insert_advocate() {
        var dno = '<?= $filing_details['diary_no'] ?>';
        var ucode = '<?= $user_details['usercode'] ?>';
        var advocate_id = document.getElementById('aorcode').value;
        var remarks = document.getElementById('remarks').value;
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        if (advocate_id < 1) {
            alert("Please Select AOR");
            return false;
        }
        if (remarks == '') {
            alert("Remarks cannot be empty");
            return false;
        }
        

        var result = confirm("Are you sure to add caveator name?");
        // console.log(result);
        // return false;
        if (result) {

            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    dno: dno,
                    adv_id: advocate_id,
                    rmk: remarks,
                    uscode: ucode
                },
                url: "<?php echo base_url('Filing/Advocate/add_advocate_writ'); ?>",
                success: function(data) {
                    // alert(data);
                    $("#message_for_disposalcase").html(data);
                    updateCSRFToken();

                },
                error: function(data) {
                    // alert(data);
                    $("#message_for_disposalcase").html(data);
                    updateCSRFToken();
                }
            });

        }
    }

    // **************************** CODE ADDED BY P.S TO ADD CAVEATOR IN WRIT MODULE IN ADVOCATE MENU ENDS HERE ***************************

    function searchBtn() {
        $('#show_view').hide();
        $('#advocate_tab_panel').hide();
        $('#add_caveator_writ').hide();
        $('#search_advocate_tab_panel').show();
        $('#update_advocate').hide();

    }

    function advocateBtn() {
        $('#advocate_tab_panel').show();
        $('#search_advocate_tab_panel').hide();
        $('#add_caveator_writ').hide();
        $('#update_advocate').hide();

    }

    function updateAdvocate() {
        $('#update_advocate').show();
        $('#advocate_tab_panel').hide();
        $('#search_advocate_tab_panel').hide();
        $('#add_caveator_writ').hide();
        $('#show_view').hide();

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            url: '<?php echo base_url('Filing/Advocate/adv_fetch_parties_first_up'); ?>',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
            },
            type: 'POST',
            beforeSend: function(xhr) {

                $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='" + base_url + "/images/load.gif'></div>");
            },
            success: function(data, status) {
                //             alert(data);
                $("#result1").html(data);
                $("#result2").html("");
                /* if(val=='D')
                {
                	$('#suc_msg').show();
                	 
                } */
                updateCSRFToken();
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
        //}


    }

    function update_advocate(i) {
        var save_ctrl = 0;
        var no = document.getElementById('all').value;
        //alert('hello');
        //alert(document.getElementById('r_no'+i).value);
        //alert(document.getElementById('adv_p_no'+i));
        var partyno = document.getElementById('adv_p_no' + i).innerText;
        var inperson_condition = '';

        if (document.getElementById('adv_pet_res' + i).value == 'P' && $("#p_span_inperson" + i).css('display') != 'none') {
            if (document.getElementById('p_inperson_mob' + i).value == '') {
                alert("Please enter mobile no.!!!");
                document.getElementById('p_inperson_mob' + i).focus();
                return;
            } else if (document.getElementById('p_inperson_email' + i).value == '') {
                alert("Please enter Email ID!!!");
                document.getElementById('p_inperson_email' + i).focus();
                return;
            }
            var inperson_mob = document.getElementById('p_inperson_mob' + i).value;
            var inperson_email = document.getElementById('p_inperson_email' + i).value;
            inperson_condition = "&inperson_mob=" + inperson_mob + "&inperson_email=" + inperson_email;

        } else if (document.getElementById('adv_pet_res' + i).value == 'R' && $("#r_span_inperson" + i).css('display') != 'none') {
            if (document.getElementById('r_inperson_mob' + i).value == '') {
                alert("Please enter mobile no.!!!");
                document.getElementById('r_inperson_mob' + i).focus();
                return;
            } else if (document.getElementById('r_inperson_email' + i).value == '') {
                alert("Please enter Email ID!!!");
                document.getElementById('r_inperson_email' + i).focus();
                return;
            }
            var inperson_mob = document.getElementById('r_inperson_mob' + i).value;
            var inperson_email = document.getElementById('r_inperson_email' + i).value;
            inperson_condition = "&inperson_mob=" + inperson_mob + "&inperson_email=" + inperson_email;
        }
        // alert(document.getElementById('adv_p_no'+i).innerText);
        if (document.getElementById('adv_no' + i) && document.getElementById('adv_no' + i).style.display != 'none') {
            if (document.getElementById('adv_name_write' + i).style.display == 'none') {
                /*if($("#adv_state"+i).val()==''){
                    alert('Please fill Advocate State');
                    document.getElementById('adv_state'+i).focus();
                    return false;
                }*/
            }

            /**/
            if ($("#span_aor" + i).css('display') != 'none') {
                if (document.getElementById('adv_aor' + i).value == '') {
                    alert('Please fill Advocate AOR Code');
                    document.getElementById('adv_aor' + i).focus();
                    return false;
                } else if (document.getElementById('adv_name' + i).innerHTML == '' || document.getElementById('adv_name' + i).innerHTML == 0) {
                    if (document.getElementById('adv_name_write' + i).style.display == 'block') {
                        alert('Please Fill Advocate Name');
                        document.getElementById('adv_name_write' + i).focus();
                    } else {
                        alert('Please fetch Proper Advocate Again');
                        document.getElementById('adv_aor' + i).focus();
                    }
                    //alert('Please fetch Proper Advocate Again');
                    //document.getElementById('adv_no'+i).focus();
                    return false;
                }
            } else if ($("#span_state" + i).css('display') != 'none') {
                if ($("#adv_state" + i).val() == '') {
                    alert('Please fill Advocate State');
                    document.getElementById('adv_state' + i).focus();
                    return false;
                } else if (document.getElementById('adv_no' + i).value == '' || document.getElementById('adv_no' + i).value == 0) {
                    alert('Please fill Advocate Enroll Number');
                    document.getElementById('adv_no' + i).focus();
                    return false;
                } else if (document.getElementById('adv_yr' + i).value == '' || document.getElementById('adv_yr' + i).value == 0) {
                    alert('Please fill Advocate Enroll Year');
                    document.getElementById('adv_yr' + i).focus();
                    return false;
                } else if (document.getElementById('adv_name' + i).innerHTML == '' || document.getElementById('adv_name' + i).innerHTML == 0) {
                    if (document.getElementById('adv_name_write' + i).style.display == 'block') {
                        alert('Please Fill Advocate Name');
                        document.getElementById('adv_name_write' + i).focus();
                    } else {
                        alert('Please fetch Proper Advocate Again');
                        document.getElementById('adv_no' + i).focus();
                    }
                    //alert('Please fetch Proper Advocate Again');
                    //document.getElementById('adv_no'+i).focus();
                    return false;
                }
            }

            if (document.getElementById('adv_p_no' + i).value == 0) {
                if (document.getElementById('adv_type' + i).value == 'N') {
                    if (document.getElementById('adv_pet_res' + i).value == 'R') {
                        alert('Party No cannot be Zero');
                        document.getElementById('adv_p_no' + i).focus();
                        return false;
                    }
                }
            }

        }


        if (document.getElementById('adv_aor' + i)) {
            var xmlhttp;
            if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            //document.getElementById('container').innerHTML = '<table widht="100%" align="center"><tr><td style=color:red><blink>Please Wait<blink></td></tr></table>';
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                    //document.getElementById('result1').innerHTML += xmlhttp.responseText;
                    //alert(xmlhttp.responseText);
                    if (xmlhttp.responseText == '1') {
                        // alert(xmlhttp.responseText);
                        //alert(document.getElementById('adv_span'+i).innerHTML);
                        //save_ctrl += xmlhttp.responseText;
                        document.getElementById('adv_span' + i).innerHTML = "Advocate Updated Successfully";
                    }
                    if (xmlhttp.responseText == '0') {
                        document.getElementById('adv_span' + i).innerHTML = "Data not updated as no change found";
                    }
                }
            }
            //alert(document.getElementById('adv_no'+i).value);
            var url = baseURL + "/filing/Advocate/save_advnew_updated?val=" + document.getElementById('adv_pet_res' + i).value +
                "&advstate=" + document.getElementById('adv_state' + i).value +
                "&advno=" + document.getElementById('adv_no' + i).value +
                "&advyr=" + document.getElementById('adv_yr' + i).value +
                "&advaor=" + document.getElementById('adv_aor' + i).value +
                "&advaor_hd=" + document.getElementById('adv_aor_hd' + i).value +
                "&adv_name=" + document.getElementById('adv_name' + i).innerHTML +
                "&adv_name_hd=" + document.getElementById('adv_name_hd' + i).value +
                "&advstate_hd=" + document.getElementById('adv_state_hd' + i).value +
                "&advno_hd=" + document.getElementById('adv_no_hd' + i).value +
                "&advyr_hd=" + document.getElementById('adv_yr_hd' + i).value +
                "&party_hd=" + document.getElementById('adv_p_no_hd' + i).innerText +
                "&advtype=" + document.getElementById('adv_type_hd' + i).value +
                "&party=" + document.getElementById('r_no' + i).value +
                //"&fi="+document.getElementById('fil_hd').value+
                //"&adv_mob="+document.getElementById('adv_mob'+i).value+"&adv_email="+document.getElementById('adv_email'+i).value+
                "&adv_type=" + document.getElementById('adv_type' + i).value + "&ifag=" + document.getElementById('ifag' + i).value +
                "&stateadv=" + document.getElementById('statepg' + i).value + "&stateadv_hd=" + document.getElementById('statepg_hd' + i).value +
                "&adv_cat=" + document.getElementById('adv_cat' + i).value + "&advsrc=" + document.getElementById('sel_adv_src' + i).value +
                "&advsrc_hd=" + document.getElementById('sel_adv_src_hd' + i).value +
                "&party_srno_show=" + partyno + inperson_condition;
            /*"&adv_side="+$("#adv_side"+i).val();*/
            //alert(url);
            xmlhttp.open("GET", url, false);
            xmlhttp.send(null);
        }
        /* if(save_ctrl==0)
             getDetails('D');
         else
             document.getElementById('result1').innerHTML += save_ctrl;*/
    }

    function checkAdvocateName() {

        if($('#show_state').css('display') == 'block')
        {
            if($('#state_id').val() == '')
            {
                alert("Please select State.");
                $('#state_id').focus();
                return false;
            }
        }

        var adv_name_get = $('#adv_name').val();
        if (adv_name_get == "") {
            alert("Please enter advocate.");
            $('#adv_name').focus();
            return false;
        }
        

    }

    function onlynumbers(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if ((charCode >= 48 && charCode <= 57) || charCode == 9 || charCode == 8 || charCode == 37 || charCode == 39) {
            return true;
        }
        return false;
    }

    $(document).ready(function() {


        var party_type = 'P';
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();

        if (party_type == 'P' || party_type == 'R') {

            $.ajax({
                url: "<?php echo base_url('Filing/advocate/get_party_data/'); ?>",
                type: "post",
                data: {
                    CSRF_TOKEN: csrf,
                    party_type: party_type
                },
                success: function(result) {
                    //console.log(result);
                    $('.showData').html(result);
                    $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function() {
                    $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });

        }


        /*code for show view data on edit page*/
        var url = '' + window.location.href + '';
        var array = url.split("/");
        var secondStr = array[6];

        if (secondStr) {
            var array1 = secondStr.split("-");
            var getPartyUrl = array1[0];

            var party_type = getPartyUrl;
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();

            if (party_type == 'P' || party_typcheckAdvocateNamee == 'R') {

                $.ajax({
                    url: "<?php echo base_url('Filing/advocate/get_party_data/'); ?>",
                    type: "post",
                    data: {
                        CSRF_TOKEN: csrf,
                        party_type: party_type
                    },
                    success: function(result) {
                        //console.log(result);
                        $('.showData').html(result);
                        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function() {
                        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });

            }
        }
        /*code for show view data on edit page*/



        $('#adv_detail').change(function() {
            
            var adv_id = $(this).val();
            if (adv_id == 'S' || adv_id == 'AC') {
                $('#show_state').css({
                    "display": "block"
                });
                $('#show_state_code').css({
                    "display": "block"
                });
                $('#title').html('Advocate No.');
                $('#aor_code').val('');
                $("input[name='adv_year']").val('');
                $('#adv_name').val('');
                $('#state_id').val('');
               
            } else {
                $('#show_state').css({
                    "display": "none"
                });
                
                $('#show_state_code').css({
                    "display": "none"
                });
                $('#show_state_code').val('');
                $('#title').html('AOR Code');
                $('#aor_code').val('');
                $("input[name='adv_year']").val();
                $('#adv_name').val('');
            }
        });

        $('.bar_detail').keyup(function() {

            var get_party_value = $('.party:checked').val();

            if (jQuery.type(get_party_value) === "undefined") {
                alert("Please select party first");
                $('#radio_selected_court1').focus();
                return false;
            }

            if($('#aor_code').val() === '')
            {
                alert("Please enter AOR Code");
                $('#aor_code').focus();
                return false;
            }

            var advocate_detail = $("select[name='adv_detail']").val();

            if (advocate_detail == 'A') {
                var adv_no = $(this).val();

                if (get_party_value == 'P') {
                    if (adv_no == 799 || adv_no == 1034 || adv_no == 1372) {
                        $('#inperson_mob_div').css({
                            'display': 'block'
                        });
                        $('#inperson_email_div').css({
                            'display': 'block'
                        });
                    } else {
                        $('#inperson_mob_div').css({
                            'display': 'none'
                        });
                        $('#inperson_email_div').css({
                            'display': 'none'
                        });
                    }
                } else if (get_party_value == 'R') {
                    if (adv_no == 800 || adv_no == 616 || adv_no == 1034 || adv_no == 1372 || adv_no == 892) {
                        $('#inperson_mob_div').css({
                            'display': 'block'
                        });
                        $('#inperson_email_div').css({
                            'display': 'block'
                        });
                    } else {
                        $('#inperson_mob_div').css({
                            'display': 'none'
                        });
                        $('#inperson_email_div').css({
                            'display': 'none'
                        });
                    }
                } else if (get_party_value == 'N') {
                    if (adv_no == 868) {
                        $('#inperson_mob_div').css({
                            'display': 'block'
                        });
                        $('#inperson_email_div').css({
                            'display': 'block'
                        });
                    } else {
                        $('#inperson_mob_div').css({
                            'display': 'none'
                        });
                        $('#inperson_email_div').css({
                            'display': 'none'
                        });
                    }
                }

                var CSRF_TOKEN = 'CSRF_TOKEN';
                var csrf = $("input[name='CSRF_TOKEN']").val();

                $.ajax({
                    url: "<?php echo base_url('Filing/advocate/bar_detail/'); ?>",
                    type: "get",
                    data: {
                        //CSRF_TOKEN: csrf,
                        adv_no: adv_no,
                        advocate_detail: advocate_detail
                    },
                    success: function(result) {
                        //console.log(result);
                        $('#adv_name').val(result);
                        // $.getJSON("<?php //echo base_url('Csrftoken'); ?>", function(result) {
                        //     $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        // });
                    },
                    error: function() {
                        // $.getJSON("<?php //echo base_url('Csrftoken'); ?>", function(result) {
                        //     $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        // });
                    }
                });
            }

        });

        $('.bar_detail_adv').keyup(function() {
            var advocate_detail = $("select[name='adv_detail']").val();

            if (advocate_detail == 'S' || advocate_detail == 'AC') {

                var CSRF_TOKEN = 'CSRF_TOKEN';
                var csrf = $("input[name='CSRF_TOKEN']").val();
                var stateID = $("select[name='state']").val();                
                var adv_no = $("input[name='aor_code']").val();
                var adv_year = $(this).val();
                if(stateID == '')
                {
                    alert('Please select State!');
                    $("select[name='state']").focus();
                    return false;
                }

                if(adv_no == '' && $('#adv_detail').val() == 'A')
                {
                    alert('Please input AOR Code!');
                    $("select[name='aor_code']").focus();
                    return false;
                }

                $.ajax({
                    url: "<?php echo base_url('Filing/advocate/bar_detail/'); ?>",
                    type: "post",
                    data: {
                        CSRF_TOKEN: csrf,
                        stateID: stateID,
                        adv_no: adv_no,
                        adv_year: adv_year,
                        advocate_detail: advocate_detail
                    },
                    success: function(result) {
                        //console.log(result);
                        $('#adv_name').val(result);
                        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function() {
                        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });
            }

        });

        $('#advocate').click(function() {
            $('#show_view').css({
                "display": "block"
            });
        });

        $('#search').click(function() {
            $('#show_view').css({
                "display": "none"
            });
        });

        $('.party').click(function() {
            var party_type = $(this).val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();

            $.ajax({
                url: "<?php echo base_url('Filing/advocate/get_party_name/'); ?>",
                type: "post",
                data: {
                    CSRF_TOKEN: csrf,
                    party_type: party_type
                },
                success: function(result) {
                    //console.log(result);
                    $('#part_name').html(result);
                    $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function() {
                    $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });

        });

        $('.partyView').click(function() {
            var party_type = $(this).val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();
            $('#view_id').html($(this).attr('attrlbl'));
            if (party_type == 'P' || party_type == 'R') {

                $.ajax({
                    url: "<?php echo base_url('Filing/advocate/get_party_data/'); ?>",
                    type: "post",
                    data: {
                        CSRF_TOKEN: csrf,
                        party_type: party_type
                    },
                    success: function(result) {
                        //console.log(result);
                        $('.showData').html(result);
                        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function() {
                        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });

            } else if (party_type == 'I' || party_type == 'N') {

                $.ajax({
                    url: "<?php echo base_url('Filing/advocate/get_party_data_imp_int/'); ?>",
                    type: "post",
                    data: {
                        CSRF_TOKEN: csrf,
                        party_type: party_type
                    },
                    success: function(result) {
                        //console.log(result);
                        $('.showData').html(result);
                        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function() {
                        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });

            }

        });

    });

    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            searching: false,
            //"buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });

    $('.party').click(function() {
        var party_type = $(this).val();

        if (party_type == 'P') {
            $("#part_name").removeAttr('disabled', 'disabled');

            $(".category").html('<option value="M" selected>Main</option><option value="A" selected>Additional</option>');

            $(".type").html('<option value="N">None</option><option value="SURETY">SURETY</option><option value="INT">INTERVENOR</option><option value="LR/S">LR/S</option><option value="DRW">DRAWNBY</option><option value="SCLSC">SCLSC</option>');

            $(".adv_detail").html('<option value="A">AOR</option><option value="S">State</option><option value="AC">NON-AOR</option>');

        }

        if (party_type == 'R') {
            $("#part_name").removeAttr('disabled', 'disabled');

            $(".category").html('<option value="M" selected>Main</option><option value="A" selected>Additional</option>');

            $(".type").html('<option value="N">None</option><option value="SURETY">SURETY</option><option value="INT">INTERVENOR</option><option value="LR/S">LR/S</option><option value="DRW">DRAWNBY</option><option value="SCLSC">SCLSC</option><option value="OBJ">OBJECTOR</option><option value="IMPL">IMPLEADER</option><option value="COMP">COMPLAINANT</option>');

            $(".adv_detail").html('<option value="A">AOR</option><option value="S">State</option><option value="AC">NON-AOR</option>');

        }

        if (party_type == 'I') {
            $("#part_name").attr('disabled', 'disabled');

            $(".category").html('<option value="A" selected>Additional</option>');

            $(".type").html('<option value="IMPL">IMPLEADER</option>');

            $(".adv_detail").html('<option value="A">AOR</option><option value="S">State</option>');
        }

        if (party_type == 'N') {
            $("#part_name").attr('disabled', 'disabled');

            $(".category").html('<option value="A" selected>Additional</option>');

            $(".type").html('<option value="INT">INTERVENOR</option>');

            $(".adv_detail").html('<option value="A">AOR</option><option value="S">State</option>');
        }
    });

    $(document).on("focus", "#adv_search_name", function() {
        $('#adv_search_by_aor').val('');
        $("#adv_search_name").autocomplete({
            source: "<?php echo base_url('Filing/advocate/get_advocate_name/'); ?>",
            width: 450,
            matchContains: true,
            minChars: 1,
            selectFirst: false,
            select: function(event, ui) {

                $('#sr_adv_div_not_found').hide();
                $('#sr_adv_div').show();
                // Set autocomplete element to display the label
                this.value = ui.item.label;
                // Store value in hidden field
                var data = ui.item.value;
                data = data.split('~');
                $("#adv_mobile_sear").html(data[0]);
                $("#adv_email_sear").html(data[1]);

                if (data[3] == 'Y') {
                    var labelAor = 'AOR';
                    $("#aor_code_hide_show").show();
                    $("#adv_aor_code_sear").html(data[2]);
                } else if (data[3] == 'N') {
                    var labelAor = 'NON-AOR';
                    $("#adv_aor_code_sear").html('');
                    $("#aor_code_hide_show").hide();
                } else {
                    var labelAor = '';
                }

                $("#adv_aor_sear").html(labelAor);
                $("#adv_name_sear").html(ui.item.label);
                // Prevent default behaviour
                return false;
            },
            focus: function(event, ui) {
                $("#adv_search_name").val(ui.item.label);
                return false;
            }
        });
    });

    $('#sr_adv_div_not_found').hide();

    $('#adv_search_by_aor').keyup(function() {

        $('#adv_search_name').val('');

        updateCSRFToken();

        var get_aor_code = $('#adv_search_by_aor').val();

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();

        $.ajax({
            url: "<?php echo base_url('Filing/advocate/get_advocate_name_by_aor_code/'); ?>",
            type: "post",
            data: {
                CSRF_TOKEN: csrf,
                get_aor_code: get_aor_code
            },
            success: function(result) {

                var returnedData = JSON.parse(result);

                if (returnedData.err == 0) {
                    $('#sr_adv_div_not_found').show();
                    $('#sr_adv_div').hide();
                } else {
                    $('#sr_adv_div').show();
                    $('#sr_adv_div_not_found').hide();
                }

                console.log(returnedData[0].name);

                $("#adv_mobile_sear").html(returnedData[0].mobile);
                $("#adv_email_sear").html(returnedData[0].email);

                if (returnedData[0].if_aor == 'Y') {
                    var labelAor = 'AOR';
                    $("#aor_code_hide_show").show();
                    $("#adv_aor_code_sear").html(returnedData[0].aor_code);
                } else if (returnedData[0].if_aor == 'N') {
                    var labelAor = 'NON-AOR';
                    $("#adv_aor_code_sear").html('');
                    $("#aor_code_hide_show").hide();
                } else {
                    var labelAor = '';
                }

                $("#adv_aor_sear").html(labelAor);
                $("#adv_name_sear").html(returnedData[0].name);

                $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function() {
                $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });

        updateCSRFToken();

    });


$(document).on("click","input[name^=button_delete_]",function(){
    //alert('ye mera india');
    //alert('called');
    var id_ = this.name.split('button_delete_');
    //alert(id_[1]);
    del_adv(id_[1]);
});

function del_adv(id)
{
    var c = confirm("Are You Sure You Want to Delete This Advocate");
    if(c==true)
    {
//        if(document.getElementById('adv_name_write'+id).style.display=='block'){
//            /*if( (document.getElementById('adv_no'+id).value!=document.getElementById('adv_no_hd'+id).value)||
//                (document.getElementById('adv_yr'+id).value!=document.getElementById('adv_yr_hd'+id).value)||
//                (document.getElementById('adv_name'+id).innerHTML!=document.getElementById('adv_name_hd'+id).value)||
//                (document.getElementById('adv_p_no'+id).value!=document.getElementById('adv_p_no_hd'+id).value))*/
//            /*if( (document.getElementById('adv_aor'+id).value!=document.getElementById('adv_aor_hd'+id).value)||
//                (document.getElementById('adv_name'+id).innerHTML!=document.getElementById('adv_name_hd'+id).value)||
//                (document.getElementById('adv_p_no'+id).value!=document.getElementById('adv_p_no_hd'+id).value))*/
//            if( (document.getElementById('adv_aor'+id).value!=document.getElementById('adv_aor_hd'+id).value)||
//                (document.getElementById('adv_p_no'+id).value!=document.getElementById('adv_p_no_hd'+id).value))
//            {
//                //alert(document.getElementById('adv_state'+id).value+'!='+document.getElementById('adv_state_hd'+id).value);
//                alert('Record Changed, Could Not Delete, Please Fetch Record Again');
//                return false;
//            }
//        }
        //else
        {
            /*if( (document.getElementById('adv_state'+id).value!=document.getElementById('adv_state_hd'+id).value)||
                (document.getElementById('adv_no'+id).value!=document.getElementById('adv_no_hd'+id).value)||
                (document.getElementById('adv_yr'+id).value!=document.getElementById('adv_yr_hd'+id).value)||
                (document.getElementById('adv_name'+id).innerHTML!=document.getElementById('adv_name_hd'+id).value)||
                (document.getElementById('adv_p_no'+id).value!=document.getElementById('adv_p_no_hd'+id).value))*/
            /*if( (document.getElementById('adv_aor'+id).value!=document.getElementById('adv_aor_hd'+id).value)||
                (document.getElementById('adv_name'+id).innerHTML!=document.getElementById('adv_name_hd'+id).value)||
                (document.getElementById('adv_p_no'+id).value!=document.getElementById('adv_p_no_hd'+id).value))*/
            if(document.getElementById('sel_adv_src'+id).value!=document.getElementById('sel_adv_src_hd'+id).value||
            (document.getElementById('adv_p_no'+id).innerText!=document.getElementById('adv_p_no_hd'+id).value)){
                alert('Record Changed, Could Not Delete, Please Fetch Record Again');
                return false;
            }
            else{
                if(document.getElementById('sel_adv_src'+id).value=='A'){
                    if(document.getElementById('adv_aor'+id).value!=document.getElementById('adv_aor_hd'+id).value){
                        alert('Record Changed, Could Not Delete, Please Fetch Record Again');
                        return false;
                    }
                }
                else if(document.getElementById('sel_adv_src'+id).value=='S'){


                        if((document.getElementById('adv_state'+id).value!=document.getElementById('adv_state_hd'+id).value)||
                        (document.getElementById('adv_no'+id).value!=document.getElementById('adv_no_hd'+id).value)||
                        (document.getElementById('adv_yr'+id).value!=document.getElementById('adv_yr_hd'+id).value && document.getElementById('adv_yr_hd'+id).value!='0')){
                            alert('Record Changed, Could Not Delete, Please Fetch Record Again');
                            return false;
                        }
                }
            }
        }
        
        var xmlhttp;
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        //document.getElementById('container').innerHTML = '<table widht="100%" align="center"><tr><td style=color:red><blink>Please Wait<blink></td></tr></table>';
        xmlhttp.onreadystatechange=function()
        {
            if(xmlhttp.readyState==4 && xmlhttp.status==200)
            {                
                if(xmlhttp.responseText === '0')
                {
                    var r = '#row'+id;
                    if(document.getElementById('sel_adv_src'+id).value=='A')
                        var row = "<tr><td colspan='11' style='text-align:center;color:red;'><b>"+document.getElementById('adv_aor'+id).value+
                            " : "+document.getElementById('adv_name'+id).innerHTML+"</b> Deleted Successfully</td></tr>";
                    else if(document.getElementById('sel_adv_src'+id).value=='S')
                        var row = "<tr><td colspan='11' style='text-align:center;color:red;'><b>"+
                            document.getElementById('adv_state'+id).options[document.getElementById('adv_state'+id).selectedIndex].text+
                            '/'+document.getElementById('adv_no'+id).value+
                            "/"+document.getElementById('adv_yr'+id).value+" : "+document.getElementById('adv_name'+id).innerHTML+"</b> Deleted Successfully</td></tr>";
                    $(r).replaceWith(row);
                }
                else{
                    document.getElementById('result12').innerHTML = xmlhttp.responseText;
                }
            }
        }
         
        var url="<?php echo base_url('Filing/advocate/del_advocate/'); ?>?val="+document.getElementById('adv_pet_res'+id).value+
        "&advstate="+document.getElementById('adv_state'+id).value+
        "&advno="+document.getElementById('adv_no'+id).value+
        "&advyr="+document.getElementById('adv_yr'+id).value+
        "&advaor="+document.getElementById('adv_aor'+id).value+
        "&adv_name="+document.getElementById('adv_name'+id).innerHTML+
        "&party="+document.getElementById('adv_p_no'+id).innerText+
        "&advtype="+document.getElementById('adv_type_hd'+id).value+
        //"&fi="+document.getElementById('fil_hd').value+"&id="+id+
        "&advsrc="+document.getElementById('sel_adv_src'+id).value;
        //alert(url);
        xmlhttp.open("GET",url,false);
        xmlhttp.send(null); 
    }
}

    function activeAdvSrc(no, src) {
        if (src == 'S') {
            $("#span_aor" + no).css('display', 'none');
            $("#span_state" + no).css('display', 'inline');
        } else if (src == 'A') {
            $("#span_aor" + no).css('display', 'inline');
            $("#span_state" + no).css('display', 'none');
        }
        var adv_name = $("#adv_name" + no).html();
        var adv_name_hd = $("#adv_name_hd" + no).val();
        if (adv_name == adv_name_hd)
            $("#adv_name" + no).html('');
        else
            $("#adv_name" + no).html(adv_name_hd);

    }

    function onlynumbersadv(evt) 
    {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        //alert(charCode);
        if ((charCode >= 48 && charCode<= 57)||(charCode >= 65 && charCode<= 90)||(charCode >= 97 && charCode<= 122)
                ||charCode==9||charCode==8||charCode==45) {
            return true;
        }
        return false;
    }



function getAdvocateAOR(no)
{
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    //document.getElementById('container').innerHTML = '<table widht="100%" align="center"><tr><td style=color:red><blink>Please Wait<blink></td></tr></table>';
    xmlhttp.onreadystatechange=function()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            var val = xmlhttp.responseText;
            //alert(val);
            val = val.split('~');
            document.getElementById('adv_name'+no).innerHTML=val[0];
            document.getElementById('adv_name_hd'+no).value=val[0];

            document.getElementById('adv_name'+no).innerHTML=val[0];
            if((document.getElementById('adv_pet_res'+no).value)=='P'){
                if (document.getElementById('adv_aor'+no).value == 799 || document.getElementById('adv_aor'+no).value == 1034 || document.getElementById('adv_aor'+no).value == 1372 ) {
                    document.getElementById('p_span_inperson' + no).style.display = 'block';
                    document.getElementById('p_inperson_mob' + no).focus();
                }
                else {
                    document.getElementById('p_span_inperson' + no).style.display = 'none';
                    document.getElementById('p_inperson_mob' + no).value = '';
                    document.getElementById('p_inperson_email' + no).value = '';
                }
            }

            if((document.getElementById('adv_pet_res'+no).value)=='R'){
                if (document.getElementById('adv_aor'+no).value == 800 || document.getElementById('adv_aor'+no).value == 616 || document.getElementById('adv_aor'+no).value == 1034 ||document.getElementById('adv_aor'+no).value == 1372 ||document.getElementById('adv_aor'+no).value == 892) {
                    document.getElementById('r_span_inperson' + no).style.display = 'block';
                    document.getElementById('r_inperson_mob' + no).focus();
                }
                else {
                    document.getElementById('r_span_inperson' + no).style.display = 'none';
                    document.getElementById('r_inperson_mob' + no).value = '';
                    document.getElementById('r_inperson_email' + no).value = '';
                }
            }

            //document.getElementById('adv_mob'+no).value=val[1];
            //document.getElementById('adv_email'+no).value=val[2];
            if(document.getElementById('adv_name_write'+no).style.display=='block'){
                document.getElementById('adv_name_write'+no).style.display='none';
                document.getElementById('adv_name'+no).style.display='inline';
                //document.getElementById('adv_mob'+no).style.display='inline';
                //document.getElementById('adv_email'+no).style.display='inline';
            }
        }
    }


    
    var url = base_url+"/Filing/advocate/get_adv_name?advt=A&advno="+document.getElementById('adv_aor'+no).value;
    xmlhttp.open("GET",url,false);
    if(document.getElementById('adv_aor'+no).value!='')
    {    
        /*if(document.getElementById('adv_no'+no).value=='9999' && document.getElementById('adv_yr'+no).value=='2014')
        {    
            if(no!=9999)
                activeAdvEntry(no);
        }
        else
        {
            if(no!=9999)
                deactiveAdvEntry(no);
            xmlhttp.send(null); 
        }*/
        xmlhttp.send(null); 
    }
}
</script>