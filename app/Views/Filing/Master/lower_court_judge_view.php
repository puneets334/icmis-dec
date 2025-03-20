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
                                    <h4 class="basic_heading">Add Lower Court Judge Name</h4>
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
                                            $attribute = array('class' => 'form-horizontal', 'name' => 'diary_search', 'id' => 'diary_search', 'autocomplete' => 'off');
                                            echo form_open(base_url('Filing/Master/Lower_court_judge/'), $attribute);
                                            ?>
                                            <center>
                                                <div class="row">
                                                    <div class="col-sm-2"></div>
                                                    <div class="col-sm-6">
                                                        <div class="col-sm-12">
                                                            <div class="form-group row">
                                                                <label for="sc_judge_code" class="col-sm-5 col-form-label">Supreme Court Judge<span class="text-red">*</span> :</label>
                                                                <div class="col-sm-7">
                                                                    <select name="sc_judge_code" id="sc_judge_code" class="form-control select2" required>
                                                                        <option value="">Select an option</option>
                                                                        <?php foreach ($judge_list as $row) { ?>
                                                                            <option value="<?php echo $row['jcode'] ?>" <?php if (!empty($param) && $row['jcode'] == $param['sc_judge_code']) { ?> selected="selected" <?php } ?>><?php echo $row['first_name'] . ' ' . $row['sur_name']; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group row">
                                                                <label for="state_id" class="col-sm-5 col-form-label">State<span class="text-red">*</span> :</label>
                                                                <div class="col-sm-7">
                                                                    <select name="state_id" id="state_id" class="form-control select2" required>
                                                                        <option value="">Select an option</option>
                                                                        <?php foreach ($state_list as $row) { ?>
                                                                            <option value="<?php echo $row['cmis_state_id'] ?>" <?php if (!empty($param) && $row['cmis_state_id'] == $param['state_id']) { ?> selected="selected" <?php } ?>><?php echo strtoupper($row['agency_state']); ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group row">
                                                                <label for="ddl_title" class="col-sm-5 col-form-label">Title<span class="text-red">*</span> :</label>
                                                                <div class="col-sm-7">
                                                                    <select name="title" id="title" class="form-control custom-select" required>
                                                                        <option value="">Select an option</option>
                                                                        <option value="CHIEF JUSTICE" <?php if (!empty($param) && $param['title'] == 'CHIEF JUSTICE') { ?> selected="selected" <?php } ?>>CHIEF JUSTICE</option>
                                                                        <option value="JUSTICE" <?php if (!empty($param) && $param['title'] == 'JUSTICE') { ?> selected="selected" <?php } ?>>JUSTICE</option>
                                                                        <option value="MEMBER" <?php if (!empty($param) && $param['title'] == 'MEMBER') { ?> selected="selected" <?php } ?>>MEMBER</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group row">
                                                                <label for="state_id" class="col-sm-5 col-form-label">First Name<span class="text-red">*</span> :</label>
                                                                <div class="col-sm-7">
                                                                    <input type="text" class="form-control" id="judge_first_name" name="judge_first_name" placeholder="Enter first name" onkeyup="this.value=this.value.replace(/[^a-z,^A-Z.\s]/g,'');" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group row">
                                                                <label for="judge_last_name" class="col-sm-5 col-form-label">SurName<span class="text-red">*</span> :</label>
                                                                <div class="col-sm-7">
                                                                    <input type="text" class="form-control" id="judge_last_name" name="judge_last_name" placeholder="Enter surname" onkeyup="this.value=this.value.replace(/[^a-z,^A-Z.\s]/g,'');" required>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>
                                                    <div class="col-sm-2"></div>
                                                </div>
                                                <button type="submit" name="save" id="save" class="btn btn-primary" value="save_data">Save</button>
                                            </center>
                                            <?php form_close(); ?>

                                            <div class="table-responsive">
                                                <table id="datatable" class="table table-striped custom-table">

                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Title Name</th>
                                                            <th>Supreme Court Judge Full Name</th>
                                                            <th>State Name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $i = 1;
                                                        foreach ($data_list as $row) { ?>
                                                            <tr>
                                                                <td><?= $i++; ?></td>
                                                                <td><?= $row['title']; ?></td>
                                                                <td><?= $row['first_name'] . ' ' . $row['sur_name']; ?></td>
                                                                <td><?= $row['state_name']; ?></td>
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
</script>
<script>
    $(document).ready(function() {
        $(document).on('change', '#sc_judge_code', function() {
            var jcode = $(this).val();
            get_judges(jcode);
        });

    });
    <?php if (!empty($param) && !empty($param['sc_judge_code']) && $param['sc_judge_code'] != null) { ?>
        var jcode = '<?= $param['sc_judge_code']; ?>';
        get_judges(jcode);
    <?php } ?>

    function get_judges(jcode) {
        var jcode = jcode;
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: '<?php echo base_url('Filing/Master/Lower_court_judge/get_judges'); ?>',
            cache: false,
            async: true,
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                jcode: jcode
            },
            type: 'GET',
            success: function(data) {
                updateCSRFToken();
                var i_data = data.split('^');
                $('#judge_first_name').val(i_data[0]);
                $('#judge_last_name').val(i_data[1]);
            },
            error: function() {
                updateCSRFToken();
            }

        });
    }
</script>
<?= view('sci_main_footer'); ?>