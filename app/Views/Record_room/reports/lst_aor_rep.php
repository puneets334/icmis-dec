<?= view('header') ?>
<style>
table.dataTable>thead .sorting,
table.dataTable>thead {
    background-color: #0d48be !important;
    color: #fff !important;
}
</style>

<link rel="stylesheet" type="text/css" href="<?= base_url('/css/aor.css') ?>">

<section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                    <div class="card-header heading">
    <div class="row">
        <div class="col-sm-10">
            <h3 class="card-title">Record Room >> Report >> A.O.R. Details</h3>
        </div>

    </div>
</div>
<br>
<div class="container-fluid">


    <?php if (empty($records)) : ?>
        <div class='well well-lg'>
            <div class='col-md-12'>
                <button class='btn btn-primary btn-xs'>Results Found---<span class='badge'>0</span></button>
            </div>
        </div>
    <?php else : ?>

        <div class='well well-lg'>
            <div class='col-md-12'>
                <button class='btn btn-primary btn-xs'>Results Found---<span class='badge'><?= count($records) ?></span></button>
            </div>
        </div>
        <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">
            <div id="printable">
                <table id="query_builder_report" class="query_builder_report table table-bordered table-striped">
                <thead>
                <tr>
                    <th class="{sorter: false}">SNo</th>
                    <th>AOR CODE</th>
                    <th>AOR NAME</th>
                    <th>Mobile</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                <?php $sno = 1; ?>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?= $sno++ ?></td>
                        <td><?= $record['bar_id'] ?></td>
                        <td><?= $record['title'].  $record['name'] ?></td>
                        <td><?= $record['mobile'] ?></td>
                        <td><?= $record['caddress'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        </div>
    <?php endif; ?>
</div>

<br/>
    </div> <!-- card div -->



</div>
<!-- /.col -->
</div>
<!-- /.row -->




</div>
<!-- /.container-fluid -->
</section>
<script>
    $(function() {
        $("#query_builder_report").DataTable({
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
<div id="div_print">
    <div id="header" style="background-color:White;"></div>
    <div id="footer" style="background-color:White;"></div>
</div>
