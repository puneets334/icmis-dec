<?= view('header') ?>
 
<link rel="stylesheet" type="text/css" href="<?= base_url('/css/aor.css') ?>">
<section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
<div class="card-header heading">
    <div class="row">
        <div class="col-sm-10">
            <h3 class="card-title">Report >>&nbsp; Cancel Records</h3>
        </div>

    </div>
</div>
<br>
<div class=" container-fluid">


    <?php if (empty($records)) : ?>
        <div class='well well-lg'>
            <div class='col-md-12'>
                <a class='btn btn-success btn-xs' href='#'>Results Found---<span class='badge'><?= count($records) ?></span></a>
            </div>
        </div>
    <?php else : ?>
        <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">
            <div id="printable">
                <table id="query_builder_report" class="query_builder_report table table-bordered table-striped"> <!-- <table border="1" bgcolor="#FBFFFD"  id="mydt"  class="tbl_hr" width="98%" cellspacing="0" -->

                    <thead>
                        <tr>
                            <th class="{sorter: false}">SNo</th>
                            <th>AOR Code</th>
                            <th>AOR Name</th>
                            <th>Cleck Code</th>
                            <th>Clerk Name</th>
                            <th>Clerks Father's Name</th>
                            <th>Registration Date</th>
                            <th>Cancellation Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sno = 1;
                        foreach ($records as $row) :   ?>
                            <tr>
                                <td><?= $sno++ ?></td>
                                <td><?= $row['aor_code'] ?></td>
                                <td><?= $row['name'] ?></td>
                                <td><?= $row['eino'] ?></td>
                                <td><?= $row['cname'] ?></td>
                                <td><?= $row['cfname'] ?></td>
                                <td><?= $row['formatted_regdate'] ?></td>
                                <td><?= $row['formatted_event_date'] ?></td>
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
<div id="div_print">
    <div id="header" style="background-color:White;"></div>
    <div id="footer" style="background-color:White;"></div>
</div>
