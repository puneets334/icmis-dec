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
            <h3 class="card-title">Record Room >> Report >> Duplicate Records</h3>
        </div>

    </div>
</div>
<br>
<div class="container-fluid">
    <div class="panel panel-info">
       
    </div>
    <?php if (empty($records)) : ?>
        <div class='well well-lg'>
            <div class='col-md-12'>
                <a class='btn btn-primary btn-xs' href='#'>Results Found---<span class='badge'><?= count($records) ?></span></a>
            </div>
        </div>
    <?php else : ?>
        <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">
            <div id="printable">
                <table id="query_builder_report" class="query_builder_report table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>SNo</th>
                            <th>AOR Code</th>
                            <th>AOR Name</th>
                            <th>Clerk Name</th>
                            <th>Clerks Father's Name</th>
                            <th>Registration Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sno = 0; ?>
                        <?php foreach ($mergedData as $item) : ?>
                            <?php if (isset($item['eino']) && isset($item['aor_code']) && isset($item['count'])) : ?>
                                <tr style="color: green;">
                                    <td><?= ++$sno ?></td>
                                    <td></td>

                                    <td>Clerk ID</td>

                                    <td><?= $item['eino'] ?></td>
                                    <td> Attached AORS</td>
                                    <td><?= $item['count'] ?></td>
                                </tr>
                            <?php elseif (is_array($item)) : ?>
                                <?php foreach ($item as $record) : ?>
                                    <tr>
                                        <td><?= ++$sno ?></td>
                                        <td><?= $record['aor_code'] ?></td>
                                        <td>AOR Name</td>
                                        <td><?= $record['cname'] ?></td>
                                        <td><?= $record['cfname'] ?></td>
                                        <td><?= $record['formatted_regdate'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
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