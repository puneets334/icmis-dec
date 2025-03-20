<div id="print_area" class="col-12 m-0 p-0">
    <div class="box box-primary" id="tachelist">
        <div class="box-header ptbnull">
            <h3 class="box-title titlefix"><?= esc($input_title) ?> (As on <?= date("d-m-Y H:i:s"); ?>)</h3>
        </div>
        <div class="box-body">
            <div class="table-responsive mailbox-messages">
                <div class="download_label d-none"><?= esc($input_title) ?> (As on <?= date("d-m-Y H:i:s"); ?>)</div>
                <table class="table table-striped table-bordered table-hover example" id="reportTable2">
                    <thead>
                        <tr>
                            <th>#</th>
                            <?php if (in_array('case_no_with_dno', $add_columns)) : ?>
                                <th>Case No.</th>
                            <?php endif; ?>
                            <?php if (in_array('diary_no', $add_columns)) : ?>
                                <th>Diary No.</th>
                            <?php endif; ?>
                            <?php if (in_array('reg_no_display', $add_columns)) : ?>
                                <th>Registration No.</th>
                            <?php endif; ?>
                            <?php if (in_array('cause_title', $add_columns)) : ?>
                                <th>Cause Title</th>
                            <?php endif; ?>
                            <?php if (in_array('coram', $add_columns)) : ?>
                                <th>Coram</th>
                            <?php endif; ?>
                            <?php if (in_array('category', $add_columns)) : ?>
                                <th>Category</th>
                            <?php endif; ?>
                            <?php if (in_array('connected_count', $add_columns)) : ?>
                                <th>No. of Connected</th>
                            <?php endif; ?>
                            <?php if (in_array('tentative_date', $add_columns)) : ?>
                                <th>Tentative List Date</th>
                            <?php endif; ?>
                            <?php if (in_array('lastorder', $add_columns)) : ?>
                                <th>Last Order</th>
                            <?php endif; ?>
                            <?php if (in_array('section', $add_columns)) : ?>
                                <th>Section</th>
                            <?php endif; ?>
                            <?php if (in_array('da', $add_columns)) : ?>
                                <th>DA</th>
                            <?php endif; ?>
                            <?php if (in_array('advocate_name', $add_columns)) : ?>
                                <th>Advocate Name</th>
                            <?php endif; ?>
                            <?php if (in_array('notice_date', $add_columns)) : ?>
                                <th>Notice Date</th>
                            <?php endif; ?>
                            <?php if (in_array('admitted_on', $add_columns)) : ?>
                                <th>Admitted On</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($report)): ?>
                            <?php $srno = 1; ?>
                            <?php foreach ($report as $row) : ?>
                                <tr>
                                    <td><?= esc($srno++); ?></td>
                                    <?php if (in_array('case_no_with_dno', $add_columns)) : ?>
                                        <td><?= esc($row['case_no_with_dno']); ?></td>
                                    <?php endif; ?>
                                    <?php if (in_array('diary_no', $add_columns)) : ?>
                                        <td><?= esc($row['diary_no']); ?></td>
                                    <?php endif; ?>
                                    <?php if (in_array('reg_no_display', $add_columns)) : ?>
                                        <td><?= esc($row['reg_no_display']); ?></td>
                                    <?php endif; ?>
                                    <?php if (in_array('cause_title', $add_columns)) : ?>
                                        <td><?= esc($row['causetitle']); ?></td>
                                    <?php endif; ?>
                                    <?php if (in_array('coram', $add_columns)) : ?>
                                        <td><?= esc($row['Coram']); ?></td>
                                    <?php endif; ?>
                                    <?php if (in_array('category', $add_columns)) : ?>
                                        <td><?= esc($row['CATEGORY']); ?></td>
                                    <?php endif; ?>
                                    <?php if (in_array('connected_count', $add_columns)) : ?>
                                        <td><?= esc($row['connected_count']); ?></td>
                                    <?php endif; ?>
                                    <?php if (in_array('tentative_date', $add_columns)) : ?>
                                        <td><?= esc($row['Next_Listing_Dt']); ?></td>
                                    <?php endif; ?>
                                    <?php if (in_array('lastorder', $add_columns)) : ?>
                                        <td><?= esc($row['lastorder']); ?></td>
                                    <?php endif; ?>
                                    <?php if (in_array('section', $add_columns)) : ?>
                                        <td><?= esc($row['SECTION']); ?></td>
                                    <?php endif; ?>
                                    <?php if (in_array('da', $add_columns)) : ?>
                                        <td><?= esc($row['DA']); ?></td>
                                    <?php endif; ?>
                                    <?php if (in_array('advocate_name', $add_columns)) : ?>
                                        <td><?= esc($row['Advocate_Name']); ?></td>
                                    <?php endif; ?>
                                    <?php if (in_array('notice_date', $add_columns)) : ?>
                                        <td><?= esc($row['Notice_Date']); ?></td>
                                    <?php endif; ?>
                                    <?php if (in_array('admitted_on', $add_columns)) : ?>
                                        <td><?= esc($row['Admitted_On']); ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= count($add_columns) + 1; ?>" class="text-center">No Records Found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var title = function() {
            return $('.download_label').text();
        };
        $('#reportTable2').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'csv',
                    title: title,
                    exportOptions: {
                        stripHtml: true
                    }
                },
                {
                    extend: 'excel',
                    title: title,
                    exportOptions: {
                        stripHtml: true
                    }
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    title: title,
                    exportOptions: {
                        stripHtml: true
                    }
                },
                {
                    extend: 'print',
                    title: title,
                    exportOptions: {
                        stripHtml: true
                    }
                }
            ]
        });
    });
</script>