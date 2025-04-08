<?= view('header') ?>
<style>
   .table thead th{
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
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
                <h3 class="card-title">Record Room >> Report >> AORs having Clerks more than 2</h3>
            </div>

        </div>
    </div>
    <br>
    <div class="container-fluid">

        <?php if (empty($records)) : ?>
            <div class='well well-lg'>
                <div class='col-md-12'>
                <button  class='btn btn-primary btn-xs'>Results Found---<span class='badge'>0 </span></button>
                </div>
            </div>
        <?php else : ?>
            <div class='well well-lg'>
                <div class='col-md-12'>
                    <button class='btn btn-primary '>Results Found <span class='badge'><?= count($records) ?></span></butto>
                </div>
            </div> <br>
            <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">
                <div id="printable">
                    <table id="query_builder_report" class="query_builder_report table table-bordered table-striped">
                        <thead class="blue-theme">
                        <tr>
                            <th>SNo</th>
                            <th>AOR Code</th>
                            <th>AOR Name</th>
                            <th>Clerk Name</th>
                            <th>Clerks Father's Name</th>
                            <th>Clerk Mobile No. </th>
                            <th>Status Details</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $csno = 1; ?>
                        <?php foreach ($records as $record) : ?>
                            <tr style='color:green' ;>
                                <td colspan='2'><?= $csno++ ?></td>
                                <td>CLERK ID</td>
                                <td><?= $record['eino'] ?></td>
                                <td>ATTACHED AORS </td>

                                <td><?= $record['aor_count'] ?></td>

                            </tr>
                            <?php $sno = 1; ?>
                            <?php foreach ($clerks[$record['eino']] as $clerk) :  ?>
                                <tr>
                                    <td><?= $sno++ ?></td>
                                    <td><?= $clerk['aor_code'] ?></td>
                                    <td><?= $clerk['name'] ?></td>
                                    <td><?= $clerk['cname'] ?></td>
                                    <td><?= $clerk['cfname'] ?></td>
                                    <td><?= $clerk['cmobile'] ?></td>
                                    <td><?= $clerk['regdate'] ?></td>
                                </tr>
                            <?php endforeach; ?>
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
                "buttons": [
                    "copy", "csv", "excel", {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    },
                    // {
                    //     extend: 'colvis',
                    //     text: 'Show/Hide'
                    // }
                ],
                "processing": true, // Changed "bProcessing" to "processing"
                "ordering": false, // Added to disable sorting
                "paging": false // Added to disable pagination

            }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');
        });
    </script>

    <div id="div_print">
        <div id="header" style="background-color:White;"></div>
        <div id="footer" style="background-color:White;"></div>
    </div>