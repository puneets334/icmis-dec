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
                                <h3 class="card-title">R & I </h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Receipt >> Add/Update</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <?php
                                            $attribute = array('class' => 'form-horizontal', 'name' => 'frmGetRIDetail', 'id' => 'frmGetRIDetail', 'autocomplete' => 'off', 'method' => 'POST');
                                            echo form_open(base_url('RI/ReceiptController/getRIDetailByDiaryNumber'), $attribute);
                                            ?>
                                            <?= csrf_field() ?>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-3 mb-3">
                                                    <button type="button" class="btn btn-success mt-26" onclick="addEditReceiptDetail(0, '<?= base_url() ?>');"><i class="fa fa-plus"></i> Receive Postal</button>
                                                </div>

                                                <div class="row align-items-center">
                                                    <div class="col-md-4">
                                                        <label class="fw-bold">Search by R&I Diary Number</label>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="diaryNo" class="me-4">Diary No</label>
                                                        <input type="text" class="form-control" placeholder="Diary No" id="diaryNo" name="diaryNo">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="diaryYear" class="me-4">Year</label>
                                                        <select class="form-control" id="diaryYear" name="diaryYear">
                                                            <?php
                                                            for ($year = date('Y'); $year >= 1950; $year--) {
                                                                echo '<option value="' . $year . '">' . $year . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-12 col-md-3 mb-3">
                                                        <button type="button" name="btn1" id="btn1" class="quick-btn mt-26" onclick="checkDiarynumber()">Submit</button>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6 pull-right">
                                                        <span style="color: red"><?php echo !empty($msg) ? $msg : ''; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php form_close(); ?>
                                        </div>
                                        <div id="res_loader"></div>
                                        <div>
                                            <h5 align="center" class="align-items-center text-danger mt-4">Postal Received</h5>
                                            <br>
                                            <?php
                                            $i = 0;
                                            $sno = 1;
                                            if (!empty($riData)) {

                                            ?>
                                                <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">
                                                    <table id="add_update_data" class="table table-bordered table-striped datatable_report">

                                                        <thead>
                                                            <tr>
                                                                <th width="7%">SNo.</th>
                                                                <th width="10%">Diary No.</th>
                                                                <th width="15%">Postal No.</th>
                                                                <th width="15%">Postal Date</th>
                                                                <th width="10%">Sender Name</th>
                                                                <th width="10%">Receipt Mode</th>
                                                                <th width="20%">Address To</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <?php
                                                                foreach ($riData as $result) { ?>

                                                                    <td><?= $sno++; ?></td>

                                                                    <td><button type="button" class="btn btn-primary" onclick="addEditReceiptDetail(<?= $result['id'] ?>, '<?= base_url() ?>')"><?= $result['postal_diary_no'] ?></button></td>
                                                                    <td><?= $result['postal_no'] ?></td>
                                                                    <td><?= !empty($result['postal_date']) ? date("d-m-Y", strtotime($result['postal_date'])) : null ?></td>
                                                                    <td><?= $result['sender_name'] ?>
                                                                        <?php
                                                                        if (!empty($result['address'])) {
                                                                            echo "<br/> Address: " . $result['address'];
                                                                        }
                                                                        ?></td>
                                                                    <td><?= $result['postal_type_description'] ?></td>
                                                                    <td><?= $result['address_to'] ?></td>

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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.section -->
<script>
    $(function() {
        $(".datatable_report").DataTable({
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
        }).buttons().container().appendTo('.query_builder_wrapper .col-md-6:eq(0)');

    });

    function checkDiarynumber() {
        var diaryNo = document.getElementById("diaryNo").value;
        var diaryYear = document.getElementById("diaryYear").value;
        if (diaryNo == "") {
            alert("Please Enter Diary Number");
            document.getElementById("diaryNo").focus();
            return false;
        }
        if (diaryYear == "") {
            alert("Please Enter Diary Year");
            document.getElementById("diaryYear").focus();
            return false;
        }
        //alert("Test");
        document.getElementById("frmGetRIDetail").submit();
    }

    function addEditReceiptDetail(ecRIId, basePath) {
        window.location.href = basePath + "/RI/ReceiptController/editReceiptData/" + ecRIId;
        //$("select#actionTaken").change();
    }
</script>