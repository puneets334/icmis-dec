<div class="card">
    <div class="card-body">
        <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">
            <?php if (!empty($ecopyStatus)) : ?>
                <table id="CopyingStats" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>SNo. </th>
                            <th>Description</th>
                            <th>Total Applications</th>
                            <th>Disposed</th>
                            <th>Pending</th>
                            <th>By Post</th>
                            <th>By Counter</th>
                            <th>Certification Charges</th>
                            <th>Service Charges</th>
                            <th>Postage</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sno = 1;
                        $rowTotal = 0;
                        $grandTotal = 0;
                        $total_appl = $disposed = $pending = $post_mode = $counter_mode = $copying_fee_in_stamp = $copying_service_charges = $postage = 0;
                        $disposed = 0;
                        $pending = 0;
                        $totalapp = 0;
                        $totalapp = 0;
                        foreach ($ecopyStatus as $row) :

                            // echo"<pre>"; print_r($row);exit;
                            $rowTotal = $row->copying_fee_in_stamp + $row->copying_service_charges + $row->postage;
                            $total_appl = $total_appl + $row->total_appl;
                            $disposed = $disposed + $row->disposed;
                            $pending = $pending + $row->total_appl;
                            $post_mode = $post_mode + $row->total_appl;
                            $counter_mode = $counter_mode + $row->total_appl;
                            $copying_fee_in_stamp = $copying_fee_in_stamp + $row->total_appl;
                            $copying_service_charges = $copying_service_charges + $row->total_appl;
                            $postage = $postage + $row->total_appl;
                            $grandTotal = $grandTotal + $rowTotal;
                        ?>
                            <tr>
                                <td><?= $sno++ ?></td>
                                <td><?= $row->code ?> - <?= $row->description ?></td>
                                <td><?= $row->total_appl ?></td>
                                <td><?= $row->disposed ?></td>
                                <td><?= $row->pending ?></td>
                                <td><?= $row->post_mode ?></td>
                                <td><?= $row->counter_mode ?></td>
                                <td><?= $row->copying_fee_in_stamp ?></td>
                                <td><?= $row->copying_service_charges ?></td>
                                <td><?= $row->postage ?></td>
                                <td><?= $rowTotal ?></td>
                            </tr>

                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>


                            <th colspan="2" class="text-right text-bold">Total </th>
                            <th><?= $total_appl ?></th>
                            <th><?= $disposed ?></th>
                            <th><?= $pending ?></th>
                            <th><?= $post_mode ?></th>
                            <th><?= $counter_mode ?></th>
                            <th><?= $copying_fee_in_stamp ?></th>
                            <th><?= $copying_service_charges ?></th>
                            <th><?= $postage ?></th>
                            <th><?= $grandTotal ?></th>


                        </tr>
                    </tfoot>
                </table>
            <?php else : ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php endif; ?>
            <!-- end of refiling search -->

        </div>
        <script>
            $(function() {
                $("#CopyingStats").DataTable({
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