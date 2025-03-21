<?php if (isset($results) && count($results) > 0): ?>
    <!-- <div style="text-align: center">
        <input type="button" name="btn_print" id='btn_print' value="Print" />
    </div> -->
    <div class="table-responsive">
        <table id="report" class="table table-striped custom-table">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Process ID/Case No/Fees/Address</th>
                    <th>Barcode</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                foreach ($results as $row):
                ?>
                    <tr>
                        <td style="width:10%">
                            <?= $sno; ?>
                        </td>
                        <td style="width:70%">
                            <div>
                                <b>PID: <?= $row['process_id']; ?>/<?= date('Y', strtotime($row['rec_dt'])); ?></b><br>
                                <?php
                                $get_case_details = get_case_details($row['diary_no']);
                                echo $get_case_details[7] . ' ' . intval(substr($get_case_details[0], 3)) . '/' . $get_case_details[1];
                                if ($get_case_details[7] == '') {
                                    echo "D.NO." . substr($row['diary_no'], 0, strlen($row['diary_no']) - 4) . "-" . substr($row['diary_no'], -4);
                                }
                                echo '/' . get_section($row['diary_no']);
                                ?>
                                &nbsp;&nbsp; (<?= 'Rs.' . $row['stamp']; ?>)
                            </div>
                            <div>
                                <?php
                                if ($row['name'] != '' && $row['copy_type'] == 0) {
                                    echo $row['name'];
                                }
                                if ($row['name'] != '' && $row['tw_sn_to'] != 0 && $row['copy_type'] == 0 && $row['send_to_type'] != 3) {
                                    echo "<br/>Through ";
                                }
                                if ($row['tw_sn_to'] != 0) {
                                    echo send_to_name($row['send_to_type'], $row['tw_sn_to']);
                                }
                                ?>
                            </div>
                            <div>
                                <?php
                                if ($row['tw_sn_to'] != 0 && $row['send_to_type'] == 3) {
                                    echo send_to_address($row['send_to_type'], $row['tw_sn_to']);
                                } else if ($row['tw_sn_to'] == 0) {
                                    echo $row['address'] . ',';
                                } else {
                                    if ($row['send_to_type'] == 1) {
                                        echo get_advocate_address($row['tw_sn_to']) . ' ';
                                    }
                                }
                                ?>
                            </div>
                            <div>
                                <?php
                                if ($row['tw_sn_to'] == 0) {
                                    echo strtoupper(get_state($row['tal_state'])) . ',' . get_district($row['tal_district']);
                                } else {
                                    echo strtoupper(get_state($row['sendto_state'])) . ',' . get_district($row['sendto_district']);
                                }
                                ?>
                            </div>
                        </td>
                        <td style="width:20%">
                            <?= $row['barcode']; ?>
                        </td>
                    </tr>

                <?php
                    $sno++;
                endforeach;
                ?>
            </tbody>
        </table>
        
    <?php else: ?>
        <div style="text-align: center"><b>No Record Found</b></div>
    <?php endif; ?>
   
    </div>

    <script>
        $(function() {
            $("#report").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "bProcessing": true,
                "extend": 'colvis',
                "text": 'Show/Hide',
                "dom": 'Bfrtip', // Enables the Buttons extension
                "buttons": [{
                    extend: 'print',
                    text: 'Print',
                    title: 'Report', // Change title in print view
                    autoPrint: true, // Automatically trigger print dialog
                    exportOptions: {
                        columns: ':visible' // Only print visible columns
                    }
                }]
            }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');
        });
    </script>