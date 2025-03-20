<div class="card">
    <div class="card-body">
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php if (!empty($file_request)) : ?>
                <table id="ReportrequestSearch" class="table table-bordered table-striped">
                    <thead>
                        <h3 style="text-align: center;">File Request</h3>
                        <tr>
                            <th>SNo. </th>
                            <th>Diary Number</th>
                            <th>Case Number</th>
                            <th>CauseTitle</th>
                            <th>Application Number</th>
                            <th>Date</th>
                            <th>Remarks</th>


                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sno = 1;
                        foreach ($file_request as $row) : ?>

                            <tr>
                                <td><?= $sno++ ?></td>
                                <td><?= $row->diary ?></td>
                                <td><?= $row->reg_no_display ?></td>
                                <td><?= $row->pet_name ?> VS <?= $row->res_name ?></td>
                                <td><?= $row->application_number_display ?></td>
                                <td><?= date('d-m-Y', strtotime($row->application_receipt)) ?></td>
                                <td> <?= $row->remarks ?></td>

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
                $("#ReportrequestSearch").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", {
                            extend: 'pdfHtml5',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: 'File Request'
                        }, {
                            extend: 'print',
                            title: 'File Request'
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