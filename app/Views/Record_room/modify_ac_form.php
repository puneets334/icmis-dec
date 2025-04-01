<?= view('header') ?>
<style>

table.dataTable>thead .sorting_disabled,
table.dataTable>thead {
    background-color: #0d48be !important;
    color: #fff !important;
}


</style>
<section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 mt-2">
                        <div class="card">
                            <div class="card-header heading">
                                <div class="row">
                                    <div class="col-sm-10">
                                        <h3 class="card-title">Record Room >> Advocate Clerk >> Modification</h3>
                                    </div>
                                    <div class="col-sm-2"></div>
                                </div>
                            </div>
                            <br>
                                <div class="col-md-12">
                                        <div class="panel panel-info">
                                            
    <?php if (empty($clerkDetails)) : ?>
        <div class='well well-lg'>
            <div class='col-md-12'>
                <a class='btn btn-success btn-xs' href='#'>Results Found---<span class='badge'><?= count($clerkDetails) ?></span></a>
            </div>
        </div>
    <?php else : ?>
        <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4 mb-5">
            <div id="printable">
                <table id="query_builder_report" class="query_builder_report table table-striped">
                    <thead>
                        <tr>
                            <th>SNo</th>
                            <th>AOR Code</th>
                            <th>AOR Name</th>
                            <th>Clerk Name</th>
                            <th>Clerks Father's Name</th>
                            <th>Clerk Id No.</th>
                            <th>Mobile Number</th>
                            <th>Registration Date</th>
                            <th>Events</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sno = 1; ?>
                        <?php foreach ($clerkDetails as $row) :   ?>
                            <tr>
                                <td><?= $sno++ ?></td>
                                <td><?= $row['aor_code'] ?></td>
                                <td><?= $row['name'] ?></td>
                                <td><?= $row['cname'] ?></td>
                                <td><?= $row['cfname'] ?></td>
                                <td><?= $row['eino'] ?></td>
                                <td><?= $row['cmobile'] ?></td>
                                <td><?= $row['formatted_regdate'] ?></td>
                                <td style="text-align: center;">
                                    <a href="<?= site_url("record_room/Record/modify?id=" . $row['id']) ?>">
                                        <i class="fa fa-edit" style="vertical-align: middle;"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
    </div>
                                    
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
<script>
    $(function() {
        $("#query_builder_report").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "pageLength": 25,
            "buttons": [
                "copy", "csv", "excel", {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL'
                },
                {
                    extend: 'colvis',
                    text: 'Show/Hide'
                }
            ],
            "processing": true, // Changed "bProcessing" to "processing"
            "ordering": false, // Added to disable sorting

        }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');
    });
</script>

<div id="div_print">
    <div id="header" style="background-color:White;"></div>
    <div id="footer" style="background-color:White;"></div>
</div>
<?php 
view('sci_main_footer');
 ?>