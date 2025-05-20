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
                                    <h4 class="basic_heading"> Add Lower Court Case Type</h4>
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
                                            echo form_open(base_url('Filing/Master/Lower_court_case_type/'), $attribute);
                                            ?>
                                            <?= csrf_field() ?>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="col-sm-12">
                                                        <div class="form-group row">
                                                            <label for="state_id" class="col-sm-5 col-form-label">State<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select name="state_id" id="state_id" class="form-control select2" required>
                                                                    <option value=" ">Select State</option>
                                                                    <?php foreach ($state_list as $row) { ?>
                                                                        <option value="<?php echo $row['cmis_state_id'] ?>"><?php echo strtoupper($row['agency_state']); ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group row">
                                                            <label for="hclc" class="col-sm-5 col-form-label">HC or LC<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select id="hclc" name="hclc" class="form-control custom-select" required>
                                                                    <option value="">Select High/Lower Court</option>
                                                                    <option value="H">High Court</option>
                                                                    <option value="L">Lower Court</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group row">
                                                            <label for="district" class="col-sm-5 col-form-label">District/Lower Court<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <select id="district" name="district" class="form-control custom-select" required>
                                                                    <option value="">Select District</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group row">
                                                            <label for="case_type_code" class="col-sm-5 col-form-label">Case Type Code<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="case_type_code" maxlength="9" name="case_type_code" placeholder="Case Type - Code" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group row">
                                                            <label for="short_name" class="col-sm-5 col-form-label">Case Type (Short Name)<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="short_name" name="short_name" placeholder="Case Type - Short Name" onkeyup="this.value=this.value.replace(/[^a-z,^A-Z()/.\s]/g,'');" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group row">
                                                            <label for="long_name" class="col-sm-5 col-form-label">Case Type (Description)<span class="text-red">*</span> :</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="long_name" name="long_name" placeholder="Case Type - Description" onkeyup="this.value=this.value.replace(/[^a-z,^A-Z()/.\s]/g,'');" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <center>
                                                        <button type="submit" name="save" id="save" class="btn btn-primary" value="save_data">Save</button>
                                                    </center>

                                                </div>
                                                <div class="col-sm-8">
                                                <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                                <table id="datatable" class="table table-striped custom-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Select State </th>
                                                                    <th>HC/LC</th>
                                                                    <th>Select District Court</th>
                                                                    <th>Case Type Code</th>
                                                                    <th>Case Type (Short Name)</th>
                                                                    <th>Case Type Description</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $i = 1;
                                                                $court_type = '';
                                                                foreach ($data_list as $row) {
                                                                    if ($row['corttyp'] == 'H') {
                                                                        $court_type = 'High Court';
                                                                    } else if ($row['corttyp'] == 'L') {
                                                                        $court_type = 'Lower Court';
                                                                    }
                                                                ?>
                                                                    <tr>
                                                                        <td><?= $i++; ?></td>
                                                                        <td><?= $row['agency_state']; ?></td>
                                                                        <td><?= $court_type; ?></td>
                                                                        <td><?= $row['district_name']; ?></td>
                                                                        <td><?= $row['case_type']; ?></td>
                                                                        <td><?= $row['type_sname']; ?></td>
                                                                        <td><?= $row['lccasename']; ?></td>
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
            ],
            "bProcessing": true,
            "extend": 'colvis',
            "text": 'Show/Hide'
        }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

    });
</script>
<script>
    $(document).ready(function() {
        //----------Get District List----------------------//
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
                    url: "<?php echo base_url('Common/Ajaxcalls/get_districts'); ?>",
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
        $('#hclc').change(function() {
            var selectedState = $('#state').val();
            var hc_or_lc = $('#hclc').val();
            //alert('selected State is : '+selectedState+'hcorlc:'+hc_or_lc);
            if (hc_or_lc == 'L') {
                $("#district").prop("disabled", false);
            } else {
                $("#district").prop("disabled", true);
            }
        });
    });
</script>
