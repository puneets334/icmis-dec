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
                                <h3 class="card-title">Receipt</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Date-Wise Received in R&I From Outside</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <?php
                                            $attribute = array('class' => 'form-horizontal', 'name' => 'push-form', 'id' => 'push-form', 'autocomplete' => 'off');
                                            echo form_open(base_url('RI/ReceiptController/dateWiseReceived'), $attribute);
                                            ?>
                                            <?= csrf_field() ?>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-3 mb-3">
                                                    <label for="">From Date</label>
                                                    <input type="text" id="fromDate" name="fromDate" class="form-control dtp" required placeholder="From Date" value="<?= !empty($fromDate) ? $fromDate : null; ?>" maxlength="10" size="10" readonly>
                                                </div>
                                                <div class="col-sm-12 col-md-3 mb-3">
                                                    <label for="">To Date</label>
                                                    <input type="text" id="toDate" name="toDate" class="form-control dtp" required placeholder="From Date" value="<?= !empty($toDate) ? $toDate : null; ?>" maxlength="10" size="10" readonly>
                                                </div>

                                                <div class="col-sm-12 col-md-3 mb-3">
                                                    <button type="submit" id="view" name="view" onclick="check(); " class="quick-btn mt-26">Submit</button>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                        <div id="res_loader"></div>
                                        <?php
                                        if (isset($fromDate) && isset($toDate)) {

                                            if (!empty($receiptData)) { ?>
                                                <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">

                                                    <table id="reportTable1" class="table table-bordered table-striped datatable_report">
                                                        <thead>
                                                            <tr>
                                                                <th>SNo.</th>
                                                                <th>Diary Number</th>
                                                                <th>Sent To</th>
                                                                <th>Postal Type, Number & Date</th>
                                                                <th>Sender Name & Address</th>
                                                                <th>Case Number</th>
                                                                <th>Remarks</th>
                                                                <th>Subject</th>
                                                                <th>Received on in R&I</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $s_no = 1;
                                                            foreach ($receiptData as $case) {

                                                            ?>
                                                                <tr>
                                                                    <td><?= $s_no++; ?></td>
                                                                    <td>
                                                                        <a href="<?= base_url(); ?>/RI/ReceiptController/completeDetail/<?= $case['id'] ?>" target="_blank">
                                                                            <?= $case['diary']; ?></a>
                                                                    </td>
                                                                    <td>
                                                                        <?= $case['address_to']; ?>
                                                                    </td>
                                                                    <td><?php
                                                                        echo $case['postal_type'] . ',&nbsp;' . $case['postal_number'] . ',&nbsp;';
                                                                        echo !empty($case['postal_date']) ?  date("d-m-Y", strtotime($case['postal_date'])) : '';
                                                                        ?>
                                                                    </td>
                                                                    <td><?php
                                                                        echo $case['sender_name'] . '&nbsp;' . $case['address'];
                                                                        ?>
                                                                    </td>
                                                                    <?php
                                                                    $diarynumber = "";
                                                                    if (!empty($case['diary_number'])) {
                                                                        $diarynumber = $case['diary_number'];
                                                                        $diarynumber = "Diary No. " . substr($diarynumber, 0, -4) . "/" . substr($diarynumber, -4) . "<br/>" . $case['reg_no_display'];
                                                                    }
                                                                    ?>
                                                                    <td><?= $diarynumber; ?></td>
                                                                    <td><?= !empty($case['remarks']) ? $case['remarks'] : null; ?></td>
                                                                    <td><?= !empty($case['subject']) ? $case['subject'] : null; ?></td>
                                                                    <td><?= $case['received_by'] ?>
                                                                        On <?= date("d-m-Y h:i:s A", strtotime($case['received_on'])) ?></td>
                                                                </tr>
                                                            <?php
                                                                $s_no++;
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php
                                            } else {
                                            ?>
                                                <div class="form-group col-sm-12">
                                                    <h4 class="text-danger">&nbsp;No Record Found!!</h4>
                                                </div>

                                        <?php }
                                        }

                                        ?>


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
    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });


    $(function() {
        $(".datatable_report").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": [{
                    extend: 'copy',
                    title: 'Date-Wise Received in R&I From Outside'
                },
                {
                    extend: 'csv',
                    title: 'Date-Wise Received in R&I From Outside'
                },
                {
                    extend: 'excel',
                    title: 'Date-Wise Received in R&I From Outside'
                },
                {
                    extend: 'print',
                    title: 'Date-Wise Received in R&I From Outside'
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    autoPrint: true,
                    title: 'Date-Wise Received in R&I From Outside'
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

    function check() {
        var fromDate = document.getElementById('fromDate').value;
        var toDate = document.getElementById('toDate').value;
        date1 = new Date(fromDate.split('-')[2], fromDate.split('-')[1] - 1, fromDate.split('-')[0]);
        date2 = new Date(toDate.split('-')[2], toDate.split('-')[1] - 1, toDate.split('-')[0]);
        if (date1 > date2) {
            alert("To Date must be greater than From date");
            return false;
        }
    }
</script>