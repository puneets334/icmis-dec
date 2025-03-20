<div class="card">
    <div class="card-body">
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php if (!empty($da_wise)) : ?>
                <table id="ReportWeekly" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Description</th>
                            <th>Order Date</th>
                            <th>Copy Category</th>
                            <th>Diary Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sno = 1;
                        foreach ($da_wise as $user) : ?>
                            <tr>
                                <td><?= $sno++ ?></td>
                                <td><?php echo $user->order_type; ?></td>
                                <td><?php echo $user->order_date; ?></td>
                                <td><?php echo $user->copy_category; ?></td>


                                <td><?php echo $user->dn . '/' . $user->dy; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>No data found.</p>
            <?php endif; ?>
        </div>
        <script>
            $(function() {
                $("#ReportWeekly").DataTable({
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