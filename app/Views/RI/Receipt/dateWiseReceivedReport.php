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
                                    <h4 class="basic_heading">Receipt By Section/Officer</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">


                                            <?php
                                            $attribute = array('class' => 'form-horizontal', 'name' => 'push-form', 'id' => 'push-form', 'autocomplete' => 'off');
                                            echo form_open(base_url('RI/ReceiptController/dateWiseReceivedByConcern'), $attribute);
                                            ?>

                                            <div class="row">
                                                <div class="col-sm-12 col-md-3 mb-3">
                                                    <label for="from">From Date: </label> <input type="text" id="fromDate" name="fromDate" class="form-control dtp" required placeholder="From Date" value="<?= !empty($fromDate) ? $fromDate : null; ?>"  maxlength="10" size="10" readonly>

                                                </div>

                                                <div class="col-sm-12 col-md-3 mb-3">
                                                    <label for="to_date">To Date:</label>
                                                    <input type="text" id="toDate" name="toDate" class="form-control dtp" required placeholder="From Date" value="<?= !empty($toDate) ? $toDate : null; ?>"  maxlength="10" size="10" readonly>
                                                </div>


                                                <div class="col-sm-12 col-md-3 mb-3">
                                                    <label for="report_type">Report Type:</label>
                                                    <?php
                                                    $options = array("All", "Received", "Returned");
                                                    ?>
                                                    <select class="form-control" name="reportType" id="reportType">
                                                        <?php
                                                        foreach ($options as $index => $option) {
                                                            if (!empty($reportType)) {
                                                                if ($reportType == $index)
                                                                    echo "<option value='" . $index . "' selected>" . $option . "</option>";
                                                            } else
                                                                echo "<option value='" . $index . "'>" . $option . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="col-sm-12 col-md-3 mb-3">

                                                    <button type="submit" id="view" name="view" onclick="check(); " class="quick-btn mt-26">View</button>
                                                </div>
                                            </div>
                                        </div>



                                    </div>

                                    <?php form_close(); ?>

                                    <div id="printable" class="row">
                                        <?php
                                        if (!empty($_POST['fromDate']) && !empty($_POST['toDate'])) {
                                            if (!empty($receivedData)) {
                                        ?>
                                                <table id="reportTable1" class="table table-striped custom-table">
                                                    <thead>
                                                        <tr>
                                                            <th width="4%">#</th>
                                                            <th width="5%">Diary Number</th>
                                                            <th width="10%">Sent To</th>
                                                            <th width="15%">Postal Type, Number & Date</th>
                                                            <th width="20%">Sender Name & Address</th>
                                                            <th width="8%">Case Number</th>
                                                            <th width="8%">Remarks</th>
                                                            <th width="5%">Subject</th>
                                                            <th width="8%">Received on in R&I</th>
                                                            <th width="8%">Dispatched By & On</th>
                                                            <th width="8%">Received/Returned By Concern & On</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $s_no = 1;
                                                        foreach ($receivedData as $case) {
                                                        ?>
                                                            <tr>
                                                                <td></td>app/Controllers/RI/ReceiptController.php
                                                                <td>
                                                                    <a href="<?= base_url(); ?>/RI/ReceiptController/completeDetail/<?= $case['id'] ?>" target="_blank">
                                                                        <?= $case['diary'] ?>
                                                                </td>

                                                                <td><?= $case['address_to'] ?>
                                                                    <?php /*if (!empty($case['judgename'])) {
                                        echo $case['judgename'];
                                    } elseif (!empty($case['officer_name'])) {
                                        echo $case['officer_name'];
                                    } else {
                                        echo $case['section_name'];
                                    }*/
                                                                    ?>
                                                                </td>
                                                                <td><?php
                                                                    echo $case['postal_type'] . ',&nbsp;' . $case['postal_number'] . ',&nbsp;' . date("d-m-Y", strtotime($case['postal_date']));
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
                                                                    $diarynumber = "Diary No. " . substr($diarynumber, 0, -4) . "/" . substr($diarynumber, -4) . "<br/>" . $case['reg_no_display'];;
                                                                }
                                                                ?>
                                                                <td><?= $diarynumber; ?></td>
                                                                <td><?= $case['remarks'] ?></td>
                                                                <td><?= $case['subject'] ?></td>
                                                                <td><?= $case['received_by'] ?>
                                                                    On <?= date("d-m-Y h:i:s A", strtotime($case['received_on'])) ?></td>
                                                                <td><?= $case['dispatched_by'] ?> <?= !empty($case['dispatched_on']) ? ' On ' . date("d-m-Y h:i:s A", strtotime($case['dispatched_on'])) : ''; ?></td>
                                                                <td><?= $case['action_taken'] ?> By <?= $case['action_taken_by'] ?><?= !empty($case['action_taken_on']) ? ' On ' . date("d-m-Y h:i:s A", strtotime($case['action_taken_on'])) : ''; ?></td>
                                                                <!--<td><?/*=$case['action_taken_by']*/
                                                                        ?> On <?/*=date("d-m-Y h:i:s A", strtotime($case['action_taken_on']))*/
                                                                                ?></td>-->
                                                            </tr>
                                                        <?php
                                                            $s_no++;
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            <?php
                                            } else {
                                            ?>
                                                <br>
                                                <div class="form-group col-sm-12">
                                                    <h4 class="text-danger">&nbsp;No Record Found!!</h4>
                                                </div>

                                        <?php }
                                        }

                                        ?>
                                    </div>




                                </div>

                            </div> <!-- card div -->

                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
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

    $(document).ready(function() {
        $('#reportTable1 thead tr').clone(true).prependTo('#reportTable1 thead');
        $('#reportTable1 thead tr:eq(0) th').each(function(i) {
            if (i != 0) {
                var title = $(this).text();
                var width = $(this).width();
                if (width > 260) {
                    width = width - 100;
                } else if (width < 100) {
                    width = width + 20;
                }
                $(this).html('<input type="text" style="width: ' + width + 'px" placeholder="' + title + '" />');

                $('input', this).on('keyup change', function() {
                    if (t.column(i).search() !== this.value) {
                        t
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            }
        });


        var t = $('#reportTable1').DataTable({
            "columnDefs": [{
                "searchable": false,
                "targets": 0,
                "targets": [5, 6, 7],
                "visible": false
            }],
            "order": [
                [1, 'asc']
            ],
            "ordering": false,
            fixedHeader: true,
            scrollX: true,
            autoFill: true,
            dom: 'Bfrtip',
            "pageLength": 25,
            buttons: [{
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                'pageLength',
                {
                    extend: 'colvis',
                    columns: ':gt(1)'
                },
            ],
            lengthMenu: [
                [10, 25, 50, -1],
                ['10 rows', '25 rows', '50 rows', 'Show all']
            ]
        });

        t.on('order.dt search.dt', function() {
            t.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
                t.cell(cell).invalidate('dom');
            });
        }).draw();
    });
</script>