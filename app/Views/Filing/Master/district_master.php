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
                                    <h4 class="basic_heading">Add District</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
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

                                            <?php  //echo $_SESSION["captcha"];
                                            $attribute = array('class' => 'form-horizontal', 'name' => 'diary_search', 'id' => 'diary_search', 'autocomplete' => 'off');
                                            echo form_open(base_url('Filing/Master/District_master/'), $attribute);
                                            ?>
                                            <?= csrf_field() ?>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-3 mb-3">
                                                    <label for="state_id">State</label>
                                                    <select name="state_id" id="state_id" class="custom-select form-control" required>
                                                        <option value=" ">Select State</option>
                                                        <?php
                                                        foreach ($state_list as $row) { ?>
                                                            <option value="<?php echo $row['state_code'] ?>" <?php if (!empty($param) && $row['state_code'] == $param['state_id']) { ?> selected="selected" <?php } ?>><?php echo strtoupper($row['agency_state']); ?></option>

                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-sm-12 col-md-3 mb-3">
                                                    <label for="district_name">District Name</label>
                                                    <input type="text" class="form-control" id="district_name" name="district_name" value="<?php if (!empty($param) && $param['district_name']) {echo $param['district_name'];} ?>" onkeyup="this.value=this.value.replace(/[^a-z,^A-Z\s]/g,'');" placeholder="Enter district name" required>
                                                </div>
                                                <div class="col-sm-12 col-md-3 mb-3">
                                                    <button type="submit" name="btn1" id="submit" class="quick-btn mt-26">Submit</button>
                                                </div>
                                            </div>
                                            <?php form_close(); ?>
                                            <div id="dv_res1"></div>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-striped custom-table">
                                                <thead>
                                                    <tr>
                                                        <th>S.No.</th>
                                                        <th>District Name</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 1;
                                                    foreach ($data_list as $row) { ?>
                                                        <tr>
                                                            <td><?= $i++; ?></td>
                                                            <td><?= $row['name']; ?></td>
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
    </div>
</section>

<script>
    $("#datatable").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": ["excel", "pdf"]
    });
    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });

    });
    $(document).ready(function() {
        $(document).on('click', '.search_type', function() {
            //alert('dddd');
            var search_type = $("input[name=search_type]:checked").val();
            if (search_type == 'C') {
                $('.casetype_section').show();
                $('.diary_section').hide();
            } else {
                $('.casetype_section').hide();
                $('.diary_section').show();
            }
            //alert('search_type='+search_type);
        });
    });
</script>