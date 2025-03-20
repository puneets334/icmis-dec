<?php if (count($data) > 0): ?>
    <div class="table-responsive">
        <table id="report" class="table table-striped custom-table">
            <thead>
                <tr>
                    <th>SNo.</th>
                    <th>Diary No.</th>
                    <?php if ($value != 107): ?>
                        <th>Parties</th>
                    <?php endif; ?>
                    <th>Dispatch By</th>
                    <th>Dispatch On</th>
                    <th>Remarks</th>
                    <?php if ($value == 107): ?>
                        <th>Tentative Listing Date [Listed For]</th>
                    <?php endif; ?>
                    <th>Receive</th>
                </tr>
            </thead>
            <tbody>


                <?php $sno = 1; ?>
                <?php foreach ($data as $row): ?>
                    <tr style="<?php echo ($row['remarks'] == 'FDR -> AOR' || $row['remarks'] == 'AOR -> FDR') ? 'background-color: #cccccc' : ''; ?>">
                        <th><?php echo $sno++; ?></th>
                        <td><?php echo substr($row['diary_no'], 0, -4) . '/' . substr($row['diary_no'], -4); ?></td>
                        <?php if ($value != 107): ?>
                            <td><?php echo $row['pet_name'] . ' <b>V/S</b> ' . $row['res_name']; ?></td>
                        <?php endif; ?>
                        <td><?php echo $row['d_by_name']; ?></td>
                        <td><?php echo date('d-m-Y h:i:s A', strtotime($row['disp_dt'])); ?></td>
                        <td><?php echo $row['remarks']; ?></td>
                        <?php if ($value == 107): ?>
                            <td>
                                <?php
                                $cur_date = date('Y-m-d');
                                $new_date = date('Y-m-d', strtotime('+1 week'));
                                if (strtotime($row['next_dt']) >= strtotime($cur_date) && strtotime($row['next_dt']) <= strtotime($new_date)) {
                                    echo $row['main_supp_flag'] == 1 || $row['main_supp_flag'] == 2 ? "<font color=red>" . $row['next_dt'] . "</font> [" . $row['board_type'] . "]" : $row['next_dt'] . " [" . $row['board_type'] . "]";
                                }
                                ?>
                            </td>
                        <?php endif; ?>
                        <td>
                            <?php if ($row['rece_dt'] == ''): ?>
                                <input type="button" id="rece<?php echo $row['uid']; ?>" value="Receive" onClick="recieve_file(this.id)" />
                            <?php else: ?>
                                Received On <?php echo !empty($row['rece_dt']) ? date('d-m-Y h:i:s A', strtotime($row['rece_dt'])) : ''; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="nofound">SORRY!!!, NO RECORD FOUND</div>
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