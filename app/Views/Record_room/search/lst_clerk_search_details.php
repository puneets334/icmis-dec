<div class="card">
    <div class="card-body">


        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 table-responsive">

            <?php if (!empty($clerks)) : ?>
                    <div style='font:bold 14px Verdana; color:#C0392B;'>Clerk Name : <?= $clerks[0]['cname'] ?> S/O <?= $clerks[0]['cfname'] ?></div>
                    <div style='font:bold 14px Verdana; color:#C0392B;'>Clerk Code : <?= $clerks[0]['eino'] ?> </div>
                <div align='right' style='font:bold 14px Verdana; color:#39C02B;'>Worked/Working Advocates List </div>
                <table id="ReportFileTrap" class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th id='sno'>SNo</th>
                            <th>AOR Code</th>
                            <th>AOR Name</th>
                            <th>Mobile</th>
                            <th>Status Details
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sno = 1; ?>
                        <?php foreach ($clerks as $clerk) : ?>
                            <tr>
                                <td><?= $sno++ ?></td>
                                <td><?= $clerk['aor_code'] ?></td>
                                <td><?= $clerk['name'] ?></td>
                                <td><?= $clerk['mobile'] ?></td>
                                <td>
                                    <?php
                                    $transactions = $model->getTransactions($clerk['id']);
                                    if (!empty($transactions)) :
                                    ?>
                                        <ul>
                                            <?php foreach ($transactions as $transaction) : ?>
                                                <li><?= $transaction['event_name'] ?> ON: <?= $transaction['remarks'] . '. ' . $transaction['formatted_event_date'] ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <?php else : {
                    echo "Record Not Found";
                }
            endif; ?>
        </div>
        <script>
            $(function() {
                $("#ReportFileTrap").DataTable({
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
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });
        </script>