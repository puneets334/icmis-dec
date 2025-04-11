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
                                <h3 class="card-title">Record Room >> Report >> Clerk Details</h3>
                            </div>

                        </div>
                    </div>
                    <br>
                    <div class="container-fluid">


                        <?php if (empty($clerkDetails)) : ?>
                            <div class='well well-lg'>
                                <div class='col-md-12'>
                                    <button class='btn btn-primary btn-xs'>Results Found---<span class='badge'>0 </span></button>
                                </div>
                            </div>
                        <?php else : ?>

                            <div class='well well-lg'>
                                <div class='col-md-12'>
                                    <button class='btn btn-primary btn-xs'>Results Found---<span class='badge'><?= count($clerkDetails); ?> </span></button>
                                </div>
                            </div>
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
                                                <th>Clerk Id No.</th>
                                                <th>Clerk Mobile No.</th>
                                                <th>Registration Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $a = 1; ?>
                                            <?php foreach ($clerkDetails as $row) : ?>
                                                <tr>
                                                    <td><?= $a++ ?></td>
                                                    <td><?= $row['aor_code'] ?></td>
                                                    <td><?= $row['name'] ?></td>
                                                    <td><?= $row['cname'] ?></td>
                                                    <td><?= $row['cfname'] ?></td>
                                                    <td><?= $row['eino'] ?></td>
                                                    <td><?= $row['cmobile'] ?></td>
                                                    <td><?= $row['formatted_regdate'] ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

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
            "searching": true,
            "pageLength": 10,
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