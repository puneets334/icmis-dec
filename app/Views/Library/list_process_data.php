<style>
table.dataTable>thead .sorting,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }

</style>
<?php if (count($cases) > 0): ?>
    <h5 style="text-align:center; font-weight:600"><?= esc($title) ?></h5>
    <table class="table table-striped table-bordered" id="unverified_matters">
        <thead>
            <tr>
                <th>S.No.</th>
                <th>Case Details</th>
                <th>AOR Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $controller = new \App\Controllers\Library\ResourcesList();
                 $i=1;   
                foreach ($cases as $case):
                 $aor_name = $controller->getAORName($case['aor_code']);
                 $case_details = $controller->getCaseDetails($case);
                 $diary_no = $case['diary_no'];
                 $case_no = $controller->getCaseNumber($diary_no);
 
                ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $case_details ?></td>
                    <td><?= $aor_name ?></td>
                    <td>
                        <button type="button" class="btn btn-primary btn_upload_modal"
                                data-case_no="<?= htmlspecialchars($case_no) ?>"
                                data-cause_title="<?= htmlspecialchars($case_details) ?>"
                                data-court_no="<?= htmlspecialchars($case['court_no']) ?>"
                                data-item_no="<?= htmlspecialchars($case['item_no']) ?>"
                                data-library_reference_material="<?= htmlspecialchars($case['id']) ?>"
                                data-diary_no="<?= htmlspecialchars($diary_no) ?>"
                                data-i_status="<?= htmlspecialchars($case['i_status']) ?>"
                                data-list_date="<?= htmlspecialchars($case['list_date']) ?>"
                                data-toggle="modal"
                                data-target="#myModal">
                            Details
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<script>
    $(function() {
        var table = $("#unverified_matters").DataTable({
            "responsive": true,
            "searching": true,
            "lengthChange": false,
            "autoWidth": false,
            "pageLength": 20,
            "buttons": [{
                    extend: 'excel',
                    text: 'Save As Excel',
                    title: '<?= $title . date("d-m-Y h-i-s A") ?>',
                    exportOptions: {  columns: [0, 1, 2]  },
                    filename: '<?= $title . date("d-m-Y h-i-s A") ?>'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Save As PDF',
                    orientation: 'portrait',
                    exportOptions: {  columns: [0, 1, 2]  },
                    pageSize: 'A4',
                    title: '<?= $title . date("d-m-Y h-i-s A") ?>',
                    filename: '<?= $title . date("d-m-Y h-i-s A") ?>'
                },
                {
                        extend: 'print',
                        exportOptions: {  columns: [0, 1, 2]  },
                        text: 'Print',
                        title: '<?= $title . date("d-m-Y h-i-s A") ?>',  // Optional: Set the title for the print page
                }
            ],
            "processing": true,
            "ordering": true,
            "paging": true
        });

        table.buttons().container().appendTo('#unverified_matters_wrapper .col-md-6:eq(0)');
    });
</script>