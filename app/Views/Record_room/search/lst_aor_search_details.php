<div class="card">
    <div class="card-body">
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php if (!empty($clerks)) : ?>
                <h4 align='center'>Clerk(s) History attached to AOR with code <?= $tvap ?> and Name : <?= $aorn ?></h4>

                <table id="ReportFileTrap" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th id='sno'>SNo</th>
                        <th>Clerk Name</th>
                        <th>Clerk's Father Name</th>
                        <th>Icard No.</th>
                        <th>Mobile No.</th>
                        <th>Registration Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $sno = 1; ?>
                    <?php foreach ($clerks as $clerk) : ?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= $clerk['cname'] ?></td>
                            <td><?= $clerk['cfname'] ?></td>
                            <td><?= $clerk['eino'] ?></td>
                            <td><?= $clerk['cmobile'] ?></td>
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