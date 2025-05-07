<?= view('header'); ?>


<style>
    .scrol {
        width: 80%;
        height: 200px;
        overflow: auto;
        margin: 0 auto;
        border: 0.6px dotted #d8d8d8;
    }

    table.dataTable>thead .sorting,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
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
                                <h3 class="card-title">IA / Document Details</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <? //=view('Filing/filing_breadcrumb'); 
                    ?>
                    <!-- /.card-header -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff;">
                                    <ul class="nav nav-pills inner-comn-tabs">
                                        <li class="nav-item"><a class="nav-link active" href="<?= base_url() ?>/Filing/Ia_documents">Insert / Modification of Docs.</a></li>
                                        <li class="nav-item"><a class="nav-link" href="<?= base_url() ?>/Filing/Ia_documents/caseBlockList_view">Case Block for Loose Doc. </a></li>
                                        <li class="nav-item"><a class="nav-link" href="<?= base_url() ?>/Filing/Ia_documents/verify_defective_view">Verfify / Defects </a></li>
                                    </ul>
                                    <?php
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'party_view_form', 'id' => 'party_view_form', 'autocomplete' => 'off');
                                    echo form_open('Filing/Ia_documents/save_iaDoc_details', $attribute);
                                    ?>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">

                                        <div class="active tab-pane" id="add_doc_tab_panel">

                                            <?php if (isset($viewData['status'])) { ?>
                                                <div class="row mt-3">
                                                    <div class="col-md-12 text-center aftersubm">

                                                        <span> <?= $viewData['status'] ?> </span>
                                                        <div>
                                                            <span><strong> Reg. Date:</strong> <?= $viewData['Reg_Date'] ?> </span>
                                                            <span> <strong>Section:</strong> <?= @$viewData['section_name'] ?> </span>
                                                            <span> <strong>Category:</strong> <?= $viewData['cate_for_doc'] ?> </span>
                                                            <span> <strong>Agency State:</strong> <?= !empty($viewData['lower_high']) ? $viewData['lower_high']['agency_state'] : '' ?> </span>

                                                        </div>
                                                        <div class="mt-2" style="font-weight: 600;">
                                                            <span style="color: blue;">Case Stage: </span>&nbsp;
                                                            <span style="color: blue;"> <?= $viewData['subhead'] ?> </span>&nbsp;&nbsp;
                                                            <span>LISTED <?= $viewData['total_no_of_listing'] ?> TIMES</span>
                                                        </div>

                                                        <div class="scrol w-100">
                                                            <div class="card-body table-responsive p-0" style="display: inline-flex;">
                                                                <div class="table-responsive">
                                                                    <table class="table table-striped custom-table single-th-table" style="text-align: left;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Petitioner</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $pc = 0;
                                                                            $petRes = $viewData['tablePetRes'];
                                                                            foreach ($petRes as $val) {
                                                                                if ($val['pet_res'] == 'P') { ?>
                                                                                    <tr>
                                                                                        <td>(<?= $val['sr_no_show'] ?>)&nbsp; <?= $val['partyname'] ?></td>
                                                                                    </tr>
                                                                            <?php $pc++;
                                                                                }
                                                                            } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="table-responsive">
                                                                    <table class="table table-striped custom-table single-th-table" style="text-align: left;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Respondant</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody style="display: block;">
                                                                            <?php
                                                                            $petRes = $viewData['tablePetRes'];
                                                                            foreach ($petRes as $val) {
                                                                                if ($val['pet_res'] == 'R') { ?>
                                                                                    <tr>
                                                                                        <td>(<?= $val['sr_no_show'] ?>)&nbsp; <?= $val['partyname'] ?></td>
                                                                                    </tr>
                                                                            <?php }
                                                                            } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>

                                                            <div class="card-body table-responsive p-0" style="display: inline-flex;">
                                                                <div class="table-responsive">
                                                                    <table class="table table-striped custom-table single-th-table" style="text-align: left;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Petitioner ADV</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody style="display: block;">
                                                                            <?php
                                                                            $pet_resAdv = $viewData['tablePetResAdvocates'];
                                                                            foreach ($pet_resAdv as $val) {
                                                                                if ($val['pet_res'] == 'P') { ?>
                                                                                    <tr>
                                                                                        <td> <?= $val['adv'] ?> -- <?= $val['aor_code'] ?> </td>
                                                                                    </tr>
                                                                            <?php }
                                                                            } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="table-responsive">
                                                                    <table class="table table-striped custom-table single-th-table" style="text-align: left;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Respondant ADV</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody style="display: block;">
                                                                            <?php
                                                                            $pet_resAdv = $viewData['tablePetResAdvocates'];
                                                                            foreach ($pet_resAdv as $val) {
                                                                                if ($val['pet_res'] == 'R') { ?>
                                                                                    <tr>
                                                                                        <td> <?= $val['adv'] ?> -- <?= $val['aor_code'] ?> </td>
                                                                                    </tr>
                                                                            <?php }
                                                                            } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>

                                                <input type='hidden' value="<?php echo $dno; ?>" id='hdfno'>
                                                <input type=hidden value='<?= $pc ?>' id='hd_pcount'>
                                                <input type="hidden" value="<?php echo $viewData['c_status']; ?>" id="case_status_searched" />

                                                <?php if (array_key_exists('chckBlockMsg', $viewData)) { ?>
                                                    <div style=text-align:center;padding:5px;color:red;font-size:16px>
                                                        <b><?= $viewData['chckBlockMsg'] ?></b>
                                                    </div>
                                                <?php } ?>

                                                <?php if ($viewData['c_status'] == 'D') { ?>
                                                    <div style=text-align:center;padding:25px;color:red;font-size:25px>
                                                        <b>IA and letter cannot be inserted as Case has been disposed</b>
                                                    </div>
                                                <?php }
                                            } else { ?>
                                                <div style=text-align:center;padding:25px;color:red;font-size:25px>
                                                    <b>SORRY, NO RECORD FOUND!!!</b>
                                                </div>
                                            <?php } ?>

                                            <!-- Add / Edit Form -->
                                            <?php if (isset($viewData['status']) && (!array_key_exists('chckBlockMsg', $viewData))) { ?>
                                                <div id="addUpdateDiv">
                                                    <div class="row mt-5">

                                                        <div class="col-md-12">
                                                            <div class="form-group clearfix">
                                                                <div class="icheck-primary d-inline">
                                                                    <input type="checkbox" id="if_efil" name="if_efil" />
                                                                    <label for="if_efil">Is E-filed </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12" id="filledBy">
                                                            <div class="form-group clearfix">
                                                                <label> Filed By: </label> &nbsp;&nbsp;
                                                                <div class="icheck-primary d-inline">
                                                                    <input type="radio" id="filed_by_aor" name="radio_filed_by" value="A">
                                                                    <label for="filed_by_aor">AOR</label>
                                                                </div>&nbsp;&nbsp;
                                                                <div class="icheck-primary d-inline">
                                                                    <input type="radio" id="filed_by_pip" name="radio_filed_by" value="P">
                                                                    <label for="filed_by_pip">Petitioner-In-Person</label>
                                                                </div>&nbsp;&nbsp;
                                                                <div class="icheck-primary d-inline">
                                                                    <input type="radio" id="filed_by_rip" name="radio_filed_by" value="R">
                                                                    <label for="filed_by_rip">Respondent-In-Person</label>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-1">
                                                        <div class="col-md-6 filed_Aor" style="display:none;">
                                                            <div class="form-group row clearfix">
                                                                <label class="col-sm-2 col-form-label">AOR Code: </label>
                                                                <div class="col-sm-9">
                                                                    <select id="aor_code" class="custom-select rounded-0 select2">
                                                                        <option value="">Select</option>
                                                                        <?php foreach ($aorList as $val) { ?>
                                                                            <option value="<?= $val['aor_code'] . '-' . $val['name'] ?>"> <?= $val['aor_code'] ?> - <?= $val['name'] ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 filed_Aor" style="display:none;">
                                                            <div class="form-group row clearfix">
                                                                <label class="col-sm-2 col-form-label">AOR Name: </label>
                                                                <div class="col-sm-9">
                                                                    <input readonly type="text" class="form-control" id="name_aor_filed_by" placeholder="AOR Name">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 filed_Pet" style="display:none;">
                                                            <div class="form-group row clearfix">
                                                                <label class="col-sm-2 col-form-label">Select Petitioner: </label>
                                                                <div class="col-sm-7">
                                                                    <!-- <input type="text" class="form-control" id="name_pet_filed_by" placeholder="Select Petitioner"> -->
                                                                    <select id="name_pet_filed_by" class="custom-select rounded-0 select2">
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 filed_Res" style="display:none;">
                                                            <div class="form-group row clearfix">
                                                                <label class="col-sm-2 col-form-label">Select Respondent: </label>
                                                                <div class="col-sm-7">
                                                                    <!-- <input type="text" class="form-control" id="name_res_filed_by" placeholder="Select Respondent"> -->
                                                                    <select id="name_res_filed_by" class="custom-select rounded-0 select2">
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-md-6 mb-2">
                                                            <div class="form-group row clearfix">
                                                                <label class="col-sm-2 col-form-label">Document: </label>
                                                                <div class="col-sm-9">
                                                                    <select class="custom-select rounded-0 select2" name="m_doc" id="m_doc">
                                                                        <option value="0">Select</option>
                                                                        <?php
                                                                        $docOptions = $viewData['docOptions'];
                                                                        foreach ($docOptions as $row) {
                                                                            $docfee = '  ::' . $row['docfee'];
                                                                            echo "<option value=" . $row['doccode'] . ">" . $row['doccode'] . ' - ' . $row['docdesc'] . $docfee . "</option>";
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 mb-2">
                                                            <div class="form-group row clearfix">
                                                                <label class="col-sm-2 col-form-label">Document Type: </label>
                                                                <div class="col-sm-9">
                                                                    <select class="custom-select rounded-0 select2" name="m_doc1" id="m_doc1" disabled>
                                                                    </select>
                                                                    <input type="hidden" id="hd_doc_type1" />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 mb-2">
                                                            <div class="form-group row clearfix">
                                                                <label class="col-sm-2 col-form-label">Amount: </label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control" id="m_amt" name="m_amt">
                                                                </div>
                                                            </div>
                                                        </div>



                                                        <div class="row col-md-9 7case" style="display:none;">
                                                            <div class="col-md-3">
                                                                <label>For Resp.</label>
                                                                <input type="text" class="form-control" id="m_resp" name="m_resp">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Fee Mode :</label>
                                                                <select name="f_mode" id="f_mode" class="custom-select rounded-0">
                                                                    <option value="">Select</option>
                                                                    <option value="O">Ordinary</option>
                                                                    <option value="R">Registered</option>
                                                                    <option value="S">Speed Post</option>
                                                                    <option value="B">Both</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Process Fees: </label>
                                                                <input type="text" class="form-control" id="m_fee1" name="m_fee1">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Postal Stamp Fees:</label>
                                                                <input type="text" class="form-control" id="m_fee2" name="m_fee2">
                                                            </div>
                                                        </div>
                                                        <div class="row col-md-12 7case mt-4 mb-3" style="display:none;">

                                                            <div class="scrol" style="width: 99%;height: 100%;">
                                                                <div class="card-body table-responsive p-0" style="display: inline-flex;">
                                                                    <table class="table table-head-fixed text-nowrap" style="text-align: left;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Respondant</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody style="display: block;">
                                                                            <?php
                                                                            $petRes = $viewData['tablePetRes'];
                                                                            $last_res = 0;
                                                                            foreach ($petRes as $val) {
                                                                                if ($val['pet_res'] == 'R') { ?>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <select id="res_sel_<?= $val['sr_no_show'] ?>" class="custom-select rounded-0" style="width: 130px;">
                                                                                                <option value="O">Ordinary</option>
                                                                                                <option value="R">Registered</option>
                                                                                                <option value="S">Speed Post</option>
                                                                                                <option value="B">Both</option>
                                                                                                <option value="H">Humdust</option>
                                                                                            </select>
                                                                                            <input type="checkbox" id="reschk_pro_<?= $val['sr_no_show'] ?>" value="<?= $val['sr_no_show'] ?>">
                                                                                            (<?= $val['sr_no_show'] ?>)&nbsp; <?= $val['partyname'] ?>
                                                                                            <br><br>
                                                                                        </td>
                                                                                    </tr>
                                                                            <?php $last_res = $val['sr_no'];
                                                                                }
                                                                            } ?>
                                                                        </tbody>
                                                                    </table>

                                                                    <table class="table table-head-fixed text-nowrap" style="text-align: left;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Petitioner</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody style="display: block;">
                                                                            <?php
                                                                            $pc = 0;
                                                                            $last_pet = 0;
                                                                            $petRes = $viewData['tablePetRes'];
                                                                            foreach ($petRes as $val) {
                                                                                if ($val['pet_res'] == 'P') { ?>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <select id="res_sel_<?= $val['sr_no_show'] ?>" class="custom-select rounded-0" style="width: 130px;">
                                                                                                <option value="O">Ordinary</option>
                                                                                                <option value="R">Registered</option>
                                                                                                <option value="S">Speed Post</option>
                                                                                                <option value="B">Both</option>
                                                                                                <option value="H">Humdust</option>
                                                                                            </select>
                                                                                            <input type="checkbox" id="reschk_pro_<?= $val['sr_no_show'] ?>" value="<?= $val['sr_no_show'] ?>">
                                                                                            (<?= $val['sr_no_show'] ?>)&nbsp; <?= $val['partyname'] ?>
                                                                                            <br><br>
                                                                                        </td>
                                                                                    </tr>
                                                                            <?php $pc++;
                                                                                    $last_pet = $val['sr_no'];
                                                                                }
                                                                            } ?>
                                                                        </tbody>
                                                                    </table>

                                                                    <input type="hidden" id="last_res" value="<?= $last_res ?>">
                                                                    <input type="hidden" id="last_pet" value="<?= $last_pet ?>">
                                                                </div>
                                                            </div>

                                                        </div>


                                                        <div class="col-md-12 mb-2">
                                                            <span style="border: none;color: #ff6666">If the desired I.A. is not in the list above, then select 'XTRA' and put the I.A. name in 'Description' field. This will treated as I.A. name.</span>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group row clearfix">
                                                                <label class="col-sm-3 col-form-label">Description: </label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control" id="m_desc" name="m_desc" placeholder="Description" onblur="replace_amp(this.id)">
                                                                    <input type="hidden" id="hd_party_flag">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 row mt-3">
                                                            <div class="col-md-8">
                                                                <div class="form-group row clearfix">
                                                                    <label class="col-sm-1 col-form-label">Remark: </label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" list="docRemarks" class="form-control" id="doc_remark" name="doc_remark" placeholder="Remarks" onblur="replace_amp(this.id)">
                                                                        <datalist id="docRemarks">
                                                                        </datalist>
                                                                        <input type="hidden" id="hd_party_flag">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group row clearfix">
                                                                    <label class="col-sm-3 col-form-label">No. of Copies: </label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control" id="no_of_copy" name="no_of_copy" placeholder="No. of Copies" onblur="replace_amp(this.id)" size="4" value="4">
                                                                        <input type="hidden" id="hd_party_flag">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mt-5 col-md-12" id="updateDetails" style="display:none;">
                                                            <div class="col-md-4">
                                                                <div class="form-group row clearfix">
                                                                    <label class="col-sm-3 col-form-label">Document No: </label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" disabled class="form-control" id="doc_num" name="doc_num">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group row clearfix">
                                                                    <label class="col-sm-3 col-form-label">Filing Date: </label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" disabled class="form-control" id="fil_date" name="fil_date">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group row clearfix">
                                                                    <label class="col-sm-3 col-form-label">Received By: </label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" disabled class="form-control" id="receivd_by" name="receivd_by">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <hr>

                                                        <div class="col-md-12 mb-4 mt-2 text-center befSubmt">
                                                            <button id="saveLoose" onclick="save_loose()" class="btn btn-primary" type="button">Save</button>
                                                            <span style="display:none;" id="updateLoose" onclick="update_loose()" class="btn btn-primary" type="button">Update</span>
                                                            <button onclick="location.reload()" class="btn btn-primary" type="button">Cancel</button>
                                                        </div>

                                                    </div>
                                                </div>
                                                <!-- /.row -->
                                            <?php } ?>

                                            <hr>

                                            <!-- Edit table -->
                                            <?php if (!array_key_exists('chckBlockMsg', $viewData)) { ?>
                                                <div class="row mt-5">
                                                    <div class="col-md-12">
                                                        <h3 class="card-title" style="float: none !important; text-align: center;">Loose Documents - Modification</h3>
                                                        <div class="table-responsive">
                                                            <table id="example1" class="table table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Action</th>
                                                                        <th>S.No.</th>
                                                                        <th>Document No </th>
                                                                        <th>Document Type</th>
                                                                        <th>Description</th>
                                                                        <th>Remark</th>
                                                                        <th>No of Copies</th>
                                                                        <th>Fee</th>
                                                                        <th>Filed By[Advocate]</th>
                                                                        <th>Filing Date</th>
                                                                        <th>Received By</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>

                                                                    <?php
                                                                    if (!empty($doc_list)) {
                                                                        $sno = 1;
                                                                        foreach ($doc_list as $ky => $row) { ?>

                                                                            <tr>
                                                                                <td>
                                                                                    <span id="<?php echo $row['diary_no'] . '~' . $row['doccode'] . '~' . $row['doccode1'] . '~' . $row['docnum'] . '~' . $row['docyear'] . '~' . $row['advocate_id'] . '~' . $row['from'] . '~' . $row['docd_id'] . '~' . $row['is_efiled'] . '~' . $row['docfee'] . '~' . $row['other1'] . '~' . $row['remark'] . '~' . $row['no_of_copy'] . '~' . $row['ent_dt'] . '~' . $row['entryuser'] . '~' . $row['kntgrp'] . '~' . $row['filedby']; ?>" onclick="update_ld(this.id)" class="btn btn-info btn-sm"><i class="fas fa-edit" aria-hidden="true"></i></span>

                                                                                    <span id="<?php echo $row['diary_no'] . '~' . $row['doccode'] . '~' . $row['doccode1'] . '~' . $row['docnum'] . '~' . $row['docyear'] . '~' . $row['advocate_id'] . '~' . $row['from'] . '~' . $row['docd_id']; ?>" onclick="delete_ld(this.id,'<?php echo $sno; ?>')" class="btn btn-danger btn-sm"><i class="fas fa-trash" aria-hidden="true"></i></span>

                                                                                </td>
                                                                                <td><?= $ky + 1 ?></td>
                                                                                <td> <?= $row['docnum'] . '/' . $row['docyear']  ?> </td>
                                                                                <td>
                                                                                    <?php
                                                                                    if ($row['doccode'] == 8) {
                                                                                        echo "I.A. - ";
                                                                                    }
                                                                                    echo $row['docdesc'];
                                                                                    if ($row['undertxt'] != '') {
                                                                                        echo '<br><span class=undertxt>' . $row['undertxt'] . '</span>';
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td><?= $row['other1']; ?></td>
                                                                                <td><?= $row['remark']; ?></td>
                                                                                <td><?= $row['no_of_copy']; ?></td>
                                                                                <td><?= $row['docfee']; ?></td>
                                                                                <td><?= $row['filedby'] . "[" . $row['advocate_id'] . "]"; ?></td>
                                                                                <td><?= date('d-m-Y h:i:s A', strtotime($row['ent_dt'])) ?></td>
                                                                                <td><?= $row['entryuser']; ?></td>
                                                                            </tr>

                                                                    <?php $sno++;
                                                                        }
                                                                    }
                                                                    ?>


                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>


                                        </div>
                                        <!-- /.add_doc_tab_panel -->


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
    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
    }

    function replace_amp(x) {
        document.getElementById(x).value = document.getElementById(x).value.trim();
        document.getElementById(x).value = document.getElementById(x).value.replace('&', ' and ');
        document.getElementById(x).value = document.getElementById(x).value.replace("'", "");
        document.getElementById(x).value = document.getElementById(x).value.replace("#", "No");
    }


    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    })

    function getDocTypeDetails() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        let dno = $("#hdfno").val()
        $.ajax({
            type: "POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                dno
            },
            url: "<?php echo base_url('Filing/Ia_documents/getDoc_type1'); ?>",
            success: function(data) {

                if (data != '') {
                    data = JSON.parse(data)
                    // console.log(data)
                    let html = ''
                    data.forEach(el => {
                        html += '<option value="' + el.value + '">' + el.label + '</option>'
                    })
                    $('#m_doc1').append(html)
                }
                updateCSRFToken();
            },
            error: function() {
                $('#m_doc1').append('')
                updateCSRFToken();
            }
        });
    }

    function getRemarksList(txt) {
        updateCSRFToken()

        setTimeout(() => {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    txt
                },
                url: "<?php echo base_url('Filing/Ia_documents/getRemarksList'); ?>",
                success: function(data) {

                    if (data != '') {
                        data = JSON.parse(data)
                        // console.log(data)
                        let html = ''
                        data.forEach(el => {
                            html += '<option value="' + el.remark_data + '">'
                        })
                        $('#docRemarks').append(html)
                    } else {
                        $('#docRemarks').html('')
                    }
                    updateCSRFToken();
                },
                error: function() {
                    $('#docRemarks').html('')
                    updateCSRFToken();
                }
            });
        }, 300);

    }

    $(document).ready(function() {



        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "print",
            {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    title: 'IA Documents Details',
                    filename: 'IA-document-details_<?php echo date("d-m-Y h:i:sa");?>'
                }]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');


        $('#get_court_details').append('<hr />');
        $('.aftersubm').append('<hr />');
        $('.befSubmt').prepend('<hr />');

        $('input[type=radio][name=radio_filed_by]').change(function() {
            updateCSRFToken();
            if (this.value == 'A') {
                $('.filed_Aor').show()
                $('.filed_Res').hide()
                $('.filed_Pet').hide()
            } else if (this.value == 'P') {
                $('.filed_Pet').show()
                $('.filed_Res').hide()
                $('.filed_Aor').hide()
            } else if (this.value == 'R') {
                $('.filed_Res').show()
                $('.filed_Pet').hide()
                $('.filed_Aor').hide()

            }

            if (this.value == 'P' || this.value == 'R') {

                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                let type = this.value
                let obj = {
                    dno: $('#hdfno').val(),
                    type
                }
                $.ajax({
                    type: "POST",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        data: obj
                    },
                    url: "<?php echo base_url('Filing/Ia_documents/getPetResList'); ?>",
                    success: function(data) {
                        if (data != '') {
                            data = JSON.parse(data)
                            let html = ''
                            data.forEach(el => {
                                html += '<option value="' + el.value + '">' + el.label + '</option>'
                            })
                            if (type == 'P') {
                                $('#name_pet_filed_by').html('<option value="">Select</option> ')
                                $('#name_pet_filed_by').append(html)
                            } else if (type == 'R') {
                                $('#name_res_filed_by').html('<option value="">Select</option> ')
                                $('#name_res_filed_by').append(html)
                            }
                            updateCSRFToken();
                        }
                    },
                    error: function() {
                        $('#name_res_filed_by').html('<option value="">Select</option> ')
                        updateCSRFToken();
                    }
                });
            }

        });

        // 7case
        $("#m_doc").change(function() {
            let valOpt = $("option:selected", this).val();
            // alert(valOpt)
            if (valOpt == 7) {
                $('.7case').show()
                $('#m_amt').attr('disabled', true)
            } else {
                $('.7case').hide()
                $('#m_amt').attr('disabled', false)
            }


            if (valOpt == 8) {

                setTimeout(() => {
                    getDocTypeDetails();
                }, 200);

                $("#m_doc1").removeAttr("disabled");
                $("#m_doc1").val("");
                $("#hd_doc_type1").val("");
            } else {
                $("#m_doc1").attr('disabled', true);
                $("#m_doc1").val("");
                $("#hd_doc_type1").val("");

                $('#m_doc1').html('')
                $('#select2-m_doc1-container').text('')
            }

            if (valOpt == 8 && document.getElementById('m_doc1').value == 19) {
                $("#m_desc").removeAttr('disabled');
            } else if (valOpt == 10) {
                $("#m_desc").attr('disabled', false);
            } else {
                $("#m_desc").attr('disabled', true);
            }


            var am = document.getElementById('m_doc').options[document.getElementById('m_doc').selectedIndex].text.split('::');
            if (document.getElementById('m_doc').value > 0 && document.getElementById('m_doc').value != 7) {
                document.getElementById('m_amt').value = am[1];
            } else {
                document.getElementById('m_amt').value = 0
            }

        })


        /*    $('input#doc_remark').on('keypress', function(e) {
                // e.preventDefault()
                // e.stopPropagation()
                if(this.value.length >= 3){
                    let txt = this.value
                    setTimeout(() => {
                        getRemarksList(txt)
                    }, 200);
                }
            }); */


    })

    $(document).on("focus", "#doc_remark", function() {
        $("#doc_remark").autocomplete({
            source: "<?php echo base_url('Filing/Ia_documents/get_remark_list'); ?>",
            width: 450,
            matchContains: true,
            minChars: 1,
            selectFirst: false,
            autoFocus: true
        });
    });



    $('#m_doc1').on('change', function() {
        let val = $("option:selected", this).val();
        let text = $("option:selected", this).text()
        $('#hd_doc_type1').val(val);
        $("#m_doc1").val(val);
        $('#select2-m_doc1-container').text($("#m_doc1 option:selected").text())


        if (document.getElementById('m_doc').value == 8 && (val == 50 || val == 63)) {
            var t_pet = document.getElementById('hd_pcount').value;
            for (var i = 1; i <= t_pet; i++) {
                document.getElementById('chk_p' + i).style.display = 'inline';
                document.getElementById('img_p' + i).style.display = 'inline';
            }
        } else {
            var t_pet = document.getElementById('hd_pcount').value;
            for (var i = 1; i <= t_pet; i++) {
                if (document.getElementById('chk_p' + i))
                    document.getElementById('chk_p' + i).style.display = 'none';
                if (document.getElementById('img_p' + i))
                    document.getElementById('img_p' + i).style.display = 'none';
            }
        }

        if (val > 0) {
            if ((document.getElementById('m_doc').value == 8 && val == 19) || (document.getElementById('m_doc').value == 9 && val == 10)) {
                document.getElementById('m_desc').disabled = false;
                document.getElementById('m_desc').focus();
            } else {
                document.getElementById('m_desc').disabled = true;
                document.getElementById('m_desc').value = '';
            }
        } //end if
        else {
            document.getElementById('m_desc').disabled = true;
            document.getElementById('m_amt').value = 0;
        }

    })


    $('#aor_code').on('change', function(e) {
        if (this.value != '') {
            let val = this.value
            let valu = val.split('-')

            setTimeout(() => {
                $('#select2-aor_code-container').text(valu[0])
                $('#name_aor_filed_by').val(valu[1])
            }, 100);
        } else {
            $('#name_aor_filed_by').val('')
        }
    })




    function save_loose() {
        var aorcode = 0;
        var filedbyname = '';
        var regNum = new RegExp('^[0-9]+$');

        updateCSRFToken();


        var if_efil = 0;
        if ($("#if_efil").is(":checked")) {
            if_efil = 1;
        }


        alert("Registry is directed not to accept any application or petition on behalf of:- \n 1) Suraj India Trust or Mr. Rajiv Daiya as per Hon'ble Court Order dated. 08-02-2018 in MA no. 1158/2017(Suraj India Trust Vs UOI) \n 2) ASOK PANDE as per Hon'ble Court Order dated. 26-10-2018 in WP(C) No. 965/2018 (ASOK PANDE Vs UOI) \n 3) MANOHAR LAL SHARMA  as per Hon'ble Court Order dated. 07-12-2018 in WP(CRL) No. 315/2018 (MANOHAR LAL SHARMA Vs ARUN JAITLEY (AT PRESENT FINANCE MINISTER))\n" +
            " 4) P1-SURAJ MISHRA and P2-ROHIT GUPTA  as per Hon'ble Court Order dated. 08-05-2019 in WP(C) No. 1328/2018 (SURAJ MISHRA AND ANR VS. UNION OF INDIA AND ANR)");


        if ($('input[type=radio][name=radio_filed_by]:checked').length == 0) {
            alert('Please Select Filed By');
            return false;
        }
        var filedby = $('input[type=radio][name=radio_filed_by]:checked').val();
        if (filedby == 'A') {
            let AORcd = $("#aor_code").val()
            if (AORcd != '') {
                let arcd = AORcd.split('-')
                if (!regNum.test(arcd[0])) {
                    alert("Please Fill AOR Code in Numeric");
                    $("#aor_code").focus();
                    return false;
                } else {
                    aorcode = arcd[0]
                    filedbyname = $("#name_aor_filed_by").val();
                }
            } else {
                alert("Please Fill AOR Code in Numeric");
                $("#aor_code").focus();
                return false;
            }

            if ($("#name_aor_filed_by").val() == '') {
                alert('Please Select Proper Advocate');
                $("#aor_code").focus();
                return false;
            }
        } else if (filedby == 'P') {
            //alert($("#name_pet_filed_by").val().trim());
            if ($("#name_pet_filed_by").val().trim() == "") {
                alert('Please Fill Filedby');
                $("#name_pet_filed_by").focus();
                return false;
            } else {
                filedbyname = $("#name_pet_filed_by").val();
            }
        } else if (filedby == 'R') {
            if ($("#name_res_filed_by").val().trim() == "") {
                alert('Please Fill Filedby');
                $("#name_res_filed_by").focus();
                return false;
            } else {
                filedbyname = $("#name_res_filed_by").val();
            }
        }

        if ($("#m_doc").val() == '0') {
            alert("Please Select Document Type");
            $("#m_doc").focus();
            return false;
        }


        if ($("#m_doc").val() == 8) {
            if ($("#m_doc1").val() == '') {
                alert("Please Select IA");
                $("#m_doc1").focus();
                return false;
            }
        }

        if (!regNum.test($("#m_amt").val())) {
            alert("Please Enter Amount in Numeric Value");
            $("#m_amt").focus();
            return false;
        }

        if (!regNum.test($("#no_of_copy").val())) {
            alert("Please Fill No. of Copies in Numeric");
            $("#no_of_copy").focus();
            return false;
        }

        var pet_master = "";
        if ($("#last_pet")) {
            for (var p = 1; p <= $("#last_pet").val(); p++) {
                if ($("#petchk_pro_" + p)) {
                    if ($("#petchk_pro_" + p).is(":checked"))
                        pet_master += $("#petchk_pro_" + p).val() + '~' + $("#pet_sel_" + p).val() + ',';
                }
            }
            pet_master = pet_master.substring(0, pet_master.length - 1);
        }
        var res_master = "";
        if ($("#last_res")) {
            for (var r = 1; r <= $("#last_res").val(); r++) {
                if ($("#reschk_pro_" + r)) {
                    if ($("#reschk_pro_" + r).is(":checked"))
                        res_master += $("#reschk_pro_" + r).val() + '~' + $("#res_sel_" + r).val() + ',';
                }
            }
            res_master = res_master.substring(0, res_master.length - 1);
        }

        var m_resp = '';
        var f_mode = '';
        var m_fee1 = '';
        var m_fee2 = '';
        if ($("#m_resp")) {
            m_resp = $("#m_resp").val();
        }
        if ($("#f_mode")) {
            f_mode = $("#f_mode").val();
        }
        if ($("#m_fee1")) {
            m_fee1 = $("#m_fee1").val();
        }
        if ($("#m_fee2")) {
            m_fee2 = $("#m_fee2").val();
        }

        var party = '';
        for (var i = 1; i <= $("#hd_pcount").val(); i++) {
            if (document.getElementById('chk_p' + i)) {
                if (document.getElementById('chk_p' + i).checked == true) {
                    party += document.getElementById('chk_p' + i).value + ',';
                }
            }
        }
        party = party.substring(0, party.length - 1);
        $("#b_save").prop('disabled', true);

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
                type: 'POST',
                url: "<?php echo base_url('Filing/Ia_documents/save_loose'); ?>",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    hdfno: $("#hdfno").val(),
                    filedby: filedbyname,
                    doccode: $("#m_doc").val(),
                    doccode1: $("#hd_doc_type1").val(),
                    docfee: $("#m_amt").val(),
                    other1: $("#m_desc").val(),
                    forresp: m_resp,
                    feemode: f_mode,
                    fee1: m_fee1,
                    fee2: m_fee2,
                    t_p: $("#hd_pcount").val(),
                    pet_master: pet_master,
                    res_master: res_master,
                    aorcode: aorcode,
                    party: party,
                    remark: $("#doc_remark").val(),
                    copy: $("#no_of_copy").val(),
                    if_efil: if_efil
                }
            })
            .done(function(msg) {
                if (msg != '') {
                    msg = JSON.parse(msg)
                    // console.log(msg.message)

                    // if($("#m_doc").val()==8){   
                    //alert('IA');
                    //alert(m_doc1.value);
                    //if(m_doc1.value=='2' || m_doc1.value=='38' || m_doc1.value=='27' || m_doc1.value=='16')
                    // mark_proposal($("#hdfno").val());
                    // }
                    //document.getElementById("dv_aaau").style.display='block';
                    // document.getElementById("IA_NO2").innerHTML=msg;
                    alert(msg.message);
                    setFields();
                    location.reload();
                } else {
                    // document.getElementById("IA_NO2").innerHTML=""; 
                }
                updateCSRFToken();
            })
            .fail(function() {
                $("#b_save").removeProp('disabled');
                alert("ERROR, Please Contact Server Room");
                updateCSRFToken();
            });
    }


    function setFields() {
        $("#b_save").removeProp('disabled');
        $("#doc_remark").val("");
        $("#m_desc").val("");
        $("#m_desc").prop('disabled', true);
        $("#no_of_copy").val("4");
        $("#m_amt").val("0");
        $("#m_doc1").val("");
        $("#m_doc1").prop('disabled', true);
        $("#hd_doc_type1").val("");
        $("#m_doc").val("0");
        $("#name_aor_filed_by").prop('readonly', true);
        $("#if_efil").prop("checked", false);


        var radio_value = $('input[name=radio_filed_by]:checked').val();
        if (radio_value == 'A') {
            $("#aor_code").val("");
            $("#name_aor_filed_by").val("");
        } else if (radio_value == 'P') {
            $("#name_pet_filed_by").val("");
        } else if (radio_value == 'R') {
            $("#name_res_filed_by").val("");
        }

    }


    function delete_ld(id, rno) {
        if (confirm('Sure to Delete')) {
            updateCSRFToken();

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('Filing/Ia_documents/del_for_ld_del'); ?>',
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        type: 'D',
                        idfull: id
                    }
                })
                .done(function(msg) {
                    alert(msg);
                    location.reload()
                    updateCSRFToken();
                })
                .fail(function() {
                    alert('ERROR, Please Contact Server Room');
                    updateCSRFToken();
                });
        } else {
            return false;
        }
    }

    var Objset = ''

    function update_ld(id) {

        let fullid = id.split('~')
        Objset = id
        // console.log("id:: ", fullid)
        // return
        let dno = fullid[0];
        let doccode = fullid[1];
        let doccode1 = fullid[2];
        let docnum = fullid[3];
        let docyear = fullid[4];
        let advid = fullid[5];
        // let src = fullid[6];
        let docid = fullid[7];
        let is_efiled = fullid[8];
        let docfee = fullid[9]
        let docdesc = fullid[10]
        let remark = fullid[11]
        let no_of_copy = fullid[12]
        let filDate = fullid[13]
        let filuser = fullid[14]
        let kntgrop = fullid[15]
        let filedby = fullid[16]

        if (is_efiled == 'Y') {
            $('#if_efil').prop('checked', true)
        }
        $('#hdfno').val(dno)

        $("#m_doc").val(doccode)
        $('#select2-m_doc-container').text($("#m_doc option:selected").text())

        if (doccode == 8) {
            setTimeout(() => {
                getDocTypeDetails();

                setTimeout(() => {
                    $("#m_doc1").val(doccode1)
                    $('#select2-m_doc1-container').text($("#m_doc1 option:selected").text())
                    $("#hd_doc_type1").val(doccode1)
                    $("#m_doc1").removeAttr("disabled");
                }, 500);
            }, 200);
        }


        $('#m_amt').val(docfee)

        $('#m_desc').val(docdesc)
        $('#doc_remark').val(remark)
        $('#no_of_copy').val(no_of_copy)


        $('#doc_num').val(docnum + '/' + docyear + '-' + kntgrop)
        $('#fil_date').val(filDate)
        $('#receivd_by').val(filuser)

        $('#updateDetails').show()


        if (advid == '' || advid == '0') {
            $('#filledBy').hide()
        } else {
            $('#filledBy').hide()
            $('#aor_code').val(advid + '-' + filedby)
            $('#name_aor_filed_by').val(filedby)
            $('#select2-aor_code-container').text(advid)
            $('#filed_by_aor').prop('checked', true)
            $('.filed_Aor').show()
            $('.filed_Res').hide()
            $('.filed_Pet').hide()
        }

        $('html').animate({
            scrollTop: 0
        }, 'slow'); //IE, FF
        $('body').animate({
            scrollTop: 0
        }, 'slow'); //chrome, don't know if Safari works

        $("#m_desc").attr('disabled', true);
        $('#saveLoose').hide()
        $('#updateLoose').show()

        return

    }

    function update_loose() {
        // console.log("fullid:: ", Objset)
        let fee = $('#m_amt').val()
        let if_efil = 0;
        if ($("#if_efil").is(":checked")) {
            if_efil = 1;
        }
        let doccode = $("#m_doc").val()
        let doccode1 = $("#hd_doc_type1").val()
        let other1 = $('#m_desc').val()
        let rem = $('#doc_remark').val()
        let aor = $('#select2-aor_code-container').text()
        let noc = $('#no_of_copy').val()
        let aor_name = $('#name_aor_filed_by').val()
        let frsp = $("#m_resp").val();

        let obj = {
            fee,
            if_efil,
            doccode,
            doccode1,
            other1,
            rem,
            aor,
            noc,
            aor_name,
            frsp
        }

        updateCSRFToken();

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
                type: 'POST',
                url: '<?php echo base_url('Filing/Ia_documents/loose_up_new'); ?>',
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    idfull: Objset,
                    data: obj
                }
            })
            .done(function(msg) {
                alert(msg);
                // $("#sar").html(msg);
                setFields();
                location.reload();
                updateCSRFToken();
            })
            .fail(function() {
                updateCSRFToken();
            });


    }
</script>