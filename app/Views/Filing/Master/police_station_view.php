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
                                    <h4 class="basic_heading">Add Police Station</h4>
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

                                            <?php
                                            $attribute = array('class' => 'form-horizontal', 'name' => 'diary_search', 'id' => 'diary_search', 'autocomplete' => 'off');
                                            echo form_open(base_url('Filing/Master/Police_station/'), $attribute);
                                            ?>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="col-sm-12">
                                                        <div class="form-group row">
                                                            <label for="state_id" class="col-sm-5 col-form-label">State<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select name="state_id" id="state_id" class="form-control select2" data-placeholder="Select a state" style="width: 100%;" required>
                                                                    <option value="">Select State</option>
                                                                    <?php
                                                                    foreach ($state_list as $row) { ?>
                                                                        <option value="<?php echo $row['state_code'] ?>"><?php echo strtoupper($row['agency_state']); ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12">
                                                        <div class="form-group row">
                                                            <label for="district" class="col-sm-5 col-form-label">District/Lower Court<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select id="district" name="district" class="form-control select2" required>
                                                                    <option value="">Select District</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12">
                                                        <div class="form-group row">
                                                            <label for="long_name" class="col-sm-5 col-form-label">Police Station Name<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="police_station_name" name="police_station_name" onkeyup="this.value=this.value.replace(/[^a-z,^A-Z/\s]/g,'');" placeholder="Police Station Name" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <center>
                                                        <button type="submit" name="save" id="save" class="btn btn-primary" value="save_data" Onclick="save_data()">Save</button>
                                                    </center>


                                                </div>






                                                <div class="col-sm-8">
                                                    <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                                        <table id="datatable" class="table table-striped custom-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>State </th>
                                                                    <th>District</th>
                                                                    <th>Police Station Name</th>
                                                                    <th>Entered On</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $i = 1;
                                                                foreach ($data_list as $row) { ?>
                                                                    <tr>
                                                                        <td><?= $i++; ?></td>
                                                                        <td><?= $row['state']; ?></td>
                                                                        <td><?= $row['district']; ?></td>
                                                                        <td><?= $row['policestndesc']; ?></td>
                                                                        <td><?= date("d-m-Y", strtotime($row['ent_time'])); ?></td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>

                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php form_close(); ?>
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
    $(function() {
        $("#datatable").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL'
                },
                {
                    extend: 'colvis',
                    text: 'Show/Hide'
                }
            ],
            "bProcessing": true,
            "extend": 'colvis',
            "text": 'Show/Hide'
        }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

    });

    $(document).ready(function() {
        $('#state_id').change(function() {

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('#district').val('');

            var get_state_id = $(this).val();
            if (get_state_id != '') {
                $.ajax({
                    type: "GET",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        state_id: get_state_id
                    },
                    url: "<?php echo base_url('Filing/Master/Police_station/getAgency'); ?>",
                    success: function(data) {
                        $('#district').html(data);
                        updateCSRFToken();
                    },
                    error: function() {
                        updateCSRFToken();
                    }
                });
            }
        });
    });
</script>