<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judges Report based on Category</title>
    <link rel="stylesheet" href="<?= base_url('css/datatables.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/buttons.datatables.min.css') ?>">
    <script src="<?= base_url('js/jquery3.3.1.js') ?>"></script>
    <script src="<?= base_url('js/datatable.min.js') ?>"></script>
    <script src="<?= base_url('js/datatables.buttons.min.js') ?>"></script>
    <script src="<?= base_url('js/buttons.flash.min.js') ?>"></script>
    <script src="<?= base_url('js/jszip.min.js') ?>"></script>
    <script src="<?= base_url('js/pdfmake.min.js') ?>"></script>
    <script src="<?= base_url('js/vfs_fonts.js') ?>"></script>
    <script src="<?= base_url('js/buttons.html5.min.js') ?>"></script>
    <script src="<?= base_url('js/buttons.print.min.js') ?>"></script>
    <style>
        /* Style code */
    </style>
</head>
<body>

<div>
    <?php if (count($results) > 0): ?>
        <h1 id="head" align="center"><b><u>Categorywise Judge Report as on <?= date('d-m-Y h:i:s A') ?></u></b></h1>
        <table id="tab">
            <thead>
            <tr style="background-color:darkgray;">
                <th>SNo</th>
                <th>Case No</th>
                <th>Cause Title</th>
                <th>Group Count</th>
                <th>Coram</th>
                <th>Subject Category</th>
                <th>Section</th>
                <th>DA</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($results as $data): ?>
                <tr>
                    <td><?= $data['sno'] ?></td>
                    <td><?= $data['Case_no'] ?></td>
                    <td><?= $data['Cause_title'] ?></td>
                    <td><?= $data['Group_count'] ?></td>
                    <td><?= $data['Coram'] ?></td>
                    <td><?= $data['Subject_category'] ?></td>
                    <td><?= $data['Section'] ?></td>
                    <td><?= $data['DA'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p id="err">No data found!</p>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function () {
        $('#tab').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel', className: 'btn btn-primary glyphicon glyphicon-list-alt',
                    filename: 'Categorywise_Judges_report_as_on <?= date('d-m-Y h:i:s A') ?>',
                    title: 'List of categorywise Judges Report as on <?= date('d-m-Y h:i:s A') ?>',
                    text: 'Export to Excel',
                    autoFilter: true,
                    sheetName: 'Sheet1'
                },
                {
                    extend: 'pdf', className: 'btn btn-primary glyphicon glyphicon-file',
                    filename: 'Categorywise_Judges_report_as_on <?= date('d-m-Y h:i:s A') ?>',
                    title: 'List of categorywise Judges Report as on <?= date('d-m-Y h:i:s A') ?>',
                    pageSize: 'A4',
                    orientation: 'landscape',
                    text: 'Save as Pdf'
                },
                {
                    extend: 'print', className: 'btn btn-primary glyphicon glyphicon-print',
                    title: 'List of categorywise Judges Report as on <?= date('d-m-Y h:i:s A') ?>',
                    pageSize: 'A4',
                    orientation: 'portrait',
                    text: 'Print'
                }
            ],
            paging: true,
            ordering: false,
            info: false,
            searching: false
        });
    });
</script>

</body>
</html>
