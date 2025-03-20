<?= view('header') ?>
<style>
    div.dataTables_wrapper div.dataTables_filter label {
        display: flex;
        justify-content: end;
    }

    div.dataTables_wrapper div.dataTables_filter label input.form-control {
        width: auto !important;
        padding: 4px;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Filing >> Master</h3>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <?= view('Filing/Master/filing_master_breadcrumb'); ?>
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading"> Add State Agency</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>
                                            <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                                            <?php if (session()->getFlashdata('error')) { ?>
                                                <div class="alert alert-danger text-white ">
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                    <?= session()->getFlashdata('error') ?>
                                                </div>
                                            <?php } else if (session("message_error")) { ?>
                                                <div class="alert alert-danger">
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                    <?= session()->getFlashdata("message_error") ?>
                                                </div>
                                            <?php } else if (session("message_success")) { ?>
                                                <div class="alert alert-success">
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                    <?= session()->getFlashdata("message_success") ?>
                                                </div>
                                            <?php } else { ?>
                                                <br />
                                            <?php } ?>

                                            <?php
                                        $attribute = array('class' => 'form-horizontal','name' => 'diary_search', 'id' => 'diary_search', 'autocomplete' => 'off');
                                        echo form_open(base_url('Filing/Master/State_agency/'), $attribute);
                                        ?>
                                        <center>
                                            <div class="row">
                                                <div class="col-sm-2"></div>
                                                <div class="col-sm-6">

                                                    <div class="col-sm-12">
                                                        <div class="form-group row">
                                                            <label for="state_id" class="col-sm-5 col-form-label">State<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select name="state_id" id="state_id" class="form-control custom-select" required>
                                                                    <option value=" ">Select State</option>
                                                                    <?php foreach ($state_list as $row) {?>
                                                                        <option value="<?php echo $row['cmis_state_id'] ?>" <?php if(!empty($param) && $row['cmis_state_id']==$param['state_id']) { ?> selected="selected" <?php } ?>><?php echo strtoupper($row['agency_state']); ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group row">
                                                            <label for="state_id" class="col-sm-5 col-form-label">State Agency<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="agency_name" name="agency_name" onkeyup="this.value=this.value.replace(/[^a-z,^A-Z,()-/.\s]/g,'');" placeholder="Enter state agency" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group row">
                                                            <label for="agency_type" class="col-sm-5 col-form-label">Agency Type<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select name="agency_type" id="agency_type" class="form-control custom-select" required>
                                                                    <option value="">Select Agency Type</option>
                                                                    <option value="1">High Court Bench</option>
                                                                    <option value="5">Agency for NCDRC and CESTAT</option>
                                                                    <option value="6">Other Agency</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-sm-2"></div>
                                            </div>
                                            <button type="submit" name="save" id ="save" class="btn btn-primary" value="save_data">Save</button>
                                        </center>
                                        <?php form_close();?>


                                        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                            <table id="datatable" class="table table-striped custom-table">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Agency Name</th>
                                                    <th>State Name</th>
                                                    <th>Agency Type</th>
                                                    <th>Create On</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $i=1; $agency_typ='';  foreach($data_list as $row) {
                                                    if ($row['agency_or_court']==1){ $agency_typ='High Court Bench'; }
                                                    else if ($row['agency_or_court']==5){ $agency_typ='Agency for NCDRC and CESTAT'; }
                                                    else if ($row['agency_or_court']==6){ $agency_typ='Other Agency'; }
                                                    ?>
                                                    <tr>
                                                        <td><?=$i++;?></td>
                                                        <td><?=$row['agency_name'];?></td>
                                                        <td><?=$row['state_name'];?></td>
                                                        <td><?=$agency_typ;?></td>
                                                        <td><?=$row['updated_on'];?></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>

                                            </table>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(function () {
            $("#datatable").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                    { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
            }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

        });

    </script>
 