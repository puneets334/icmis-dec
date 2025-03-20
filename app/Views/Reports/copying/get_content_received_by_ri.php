<div class="card">
    <div class="card-body">
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php if (!empty($receivedByRI)) :

                $from_date = date("d-m-Y", strtotime($from_date));
                $to_date = date("d-m-Y", strtotime($to_date));
                $title = "eCopying Reports : Envelopes Received by R & I Section from Copying Section: Dated ";

                $title .= $from_date . " to " . $to_date;
            ?>
                <table id="ReceivedbyRI" class="table table-bordered table-striped">
                    <thead>
                        <h3 style="text-align: center;"><?= $title ?></h3>
                        <tr>
                            <th>SNo.</th>
                            <th>Application Details</th>
                            <th>Applicant Details</th>
                            <th>Barcode</th>
                            <th>Received By</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sno = 1;
                        foreach ($receivedByRI as $row) : ?>
                            <tr>
                                <td><?= $sno++ ?></td>
                                <td><?= $row->application_number_display ?><br>CRN:<?= $row->crn ?><br>SP Charges:<?= $row->postal_fee ?><br>Weight:<?= $row->envelope_weight ?></td>
                                <td><?= $row->name ?><br><u>Address:</u><?= $row->address ?><br><u>Mobile</u>:<?= $row->mobile ?><br><u>Email</u>:<?= $row->email ?></td>
                                <td><?= $row->barcode ?></td>
                                <td><?= $row->username . " [" . $row->empid . "]" ?><br><?= isset($row->received_on) ? date('d-m-Y H:i:s', strtotime($row->received_on)) : '' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    </tfoot>
                </table>
            <?php else : ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php endif; ?>
            <!-- end of refiling search -->

        </div>
        <script>
            $(function() {
                var title = '<?= $title ?>';
                $("#ReceivedbyRI").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", {
                            extend: 'pdfHtml5',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: title
                        }, {
                            extend: 'print',
                            title: title
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