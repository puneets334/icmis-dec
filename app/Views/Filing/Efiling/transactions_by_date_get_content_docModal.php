<div id="docModal_Data">
    <div class="ng-binding ng-scope">
        <?php
        if (isset($result) && !empty($result)) { ?>
            <b>CauseTitle: </b><?= $result[0]['pet_name'] . ' vs ' . $result[0]['res_name']; ?><br></p>
            <b>Applied By: </b><?= $result[0]['name']; ?><br>
            <b>Email: </b><?= $result[0]['email']; ?><br>
            <b>Mobile: </b><?= $result[0]['mobile_no']; ?><br>
            <p></p>
            <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">
                <table id="datatable_report" class="table table-bordered table-striped datatable_report">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Document</th>
                            <th>PDF</th>
                            <th>Entry date</th>
                            <th>Pages</th>
                            <th>Source</th>
                            <th>Txn. No.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($result as $row) { ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $row['main_doc'] . $row['sub_doc']; ?></td>
                                <td> <a href="<?= $row['pdf_file']; ?>" target="_blank"> View </a></td>
                                <td><?= (!empty($row['entdt']) && $row['entdt'] != null) ? date('d-m-Y H:i:s', strtotime($row['entdt'])) : ''; ?></td>
                                <td><?= (!empty($row['np']) && $row['np'] != null) ? $row['np'] : ''; ?></td>
                                <td><?= (!empty($row['source_flag']) && $row['source_flag'] != null) ? $row['source_flag'] : ''; ?></td>
                                <td><?= (!empty($row['transaction_id']) && $row['transaction_id'] != null) ? $row['transaction_id'] : ''; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>
            </div>
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
                            // {
                            //     extend: 'colvis',
                            //     text: 'Show/Hide'
                            // }
                        ],
                        "bProcessing": true,
                        "extend": 'colvis',
                        "text": 'Show/Hide'
                    }).buttons().container().appendTo('.query_builder_wrapper .col-md-6:eq(0)');

                });
            </script>
        <?php } ?>
        <?php if (isset($resultSCEFM) && !empty($resultSCEFM)) { ?>
            <br />
            <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper2">
                <p>
                <h3>SC-eFM</h3>
                </p><br />
                <table id="datatable_report" class="table table-bordered table-striped datatable_report2">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Document</th>
                            <th>Entry date</th>
                            <th>Type</th>
                            <th>E-Filing No.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($resultSCEFM as $row) { ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $row['docdesc']; ?></td>
                                <td><?= (!empty($row['created_at']) && $row['created_at'] != null) ? date('d-m-Y H:i:s', strtotime($row['created_at'])) : ''; ?></td>
                                <td><?= $row['efiled_type']; ?></td>
                                <td><a target="_blank" href="<?php echo E_FILING_URL ?>/efiling_search/DefaultController/?efiling_number=<?= $row['efiling_no']; ?>"><?= $row['efiling_no']; ?></a></td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>
            </div>

            <script>
                $(function() {
                    $(".datatable_report2").DataTable({
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "buttons": ["copy", "csv", "excel", {
                                extend: 'pdfHtml5',
                                orientation: 'landscape',
                                pageSize: 'LEGAL'
                            },
                            // {
                            //     extend: 'colvis',
                            //     text: 'Show/Hide'
                            // }
                        ],
                        "bProcessing": true,
                        "extend": 'colvis',
                        "text": 'Show/Hide'
                    }).buttons().container().appendTo('.query_builder_wrapper2 .col-md-6:eq(0)');

                });
            </script>
        <?php } ?>


        <?php
        if (isset($documents) && !empty($documents)) { ?>
            <b>Applied By: </b><?= $documents[0]['name']; ?><br>
            <b>Email: </b><?= $documents[0]['email']; ?><br>
            <b>Mobile: </b><?= $documents[0]['mobile_no']; ?><br>
            <p></p>
            <div class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper_documents">
                <table class="table table-bordered table-striped datatable_report_documents">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Document</th>
                            <th>PDF</th>
                            <th>From Page</th>
                            <th>To Page</th>
                            <th>Pages</th>
                            <th>Source</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($documents as $row) { ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $row['docdesc']; ?></td>
                                <td> <a href="<?= $row['pdf_file']; ?>" target="_blank"> View </a></td>
                                <td><?= $row['fp']; ?></td>
                                <td><?= $row['tp']; ?></td>
                                <td><?= $row['np']; ?></td>
                                <td><?= (!empty($row['source_flag']) && $row['source_flag'] != null) ? $row['source_flag'] : ''; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>
            </div>
            <script>
                $(function() {
                    $(".datatable_report_documents").DataTable({
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "buttons": ["copy", "csv", "excel", {
                                extend: 'pdfHtml5',
                                orientation: 'landscape',
                                pageSize: 'LEGAL'
                            },
                            // {
                            //     extend: 'colvis',
                            //     text: 'Show/Hide'
                            // }
                        ],
                        "bProcessing": true,
                        "extend": 'colvis',
                        "text": 'Show/Hide'
                    }).buttons().container().appendTo('.query_builder_wrapper_documents .col-md-6:eq(0)');

                });
            </script>
        <?php } ?>

        <?php if (isset($result) && empty($result) && (isset($resultSCEFM) && empty($resultSCEFM) && empty($documents))) { ?>
            <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
        <?php } ?>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
    </div>
</div>