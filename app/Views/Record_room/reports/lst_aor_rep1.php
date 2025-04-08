<?= view('header') ?>
<style>
   .table thead th{
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }
</style>
    <!-- <link rel="stylesheet" type="text/css" href="<?= base_url('/css/aor.css') ?>"> -->
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
    <div class=" container-fluid">
        

        <?php if (empty($records)) : ?>
            <div class='well well-lg'>
                <div class='col-md-12'>
                    <button class='btn btn-primary btn-xs'>Results Found---<span class='badge'>0 </span></button>
                </div>
            </div>
        <?php else : ?>

            <!-- <div class='well well-lg'>
              
                    <button class='btn btn-primary' >Results Found <span class='badge'><?=$records[0]['clerk_count']; ?></span></button>
              
            </div>  -->
            <br>

            <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">
                <div id="printable">
                    <table id="" class="query_builder_report table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>SNo</th>
                            <th>Clerk Code</th>
                            <th>Clerk Name</th>
                            <th>Clerks Father's Name</th>
                            <th>Action Details</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sno = 0;
                        foreach ($mergedData as $record) : ?>
                            <tr style="color: green; font-weight: bold;">
                                <td><?= ++$sno ?></td>
                                <td><?= $record['aor_code']; ?></td>
                                <td><?= $record['name']; ?></td>
                                <td>CLERKS ATTACHED</td>
                                <td><?= count($record['clerks']); ?></td>
                            </tr>
                        
                            <?php
                            $sno1 = 0;
                            foreach ($record['clerks'] as $clerk) : ?>
                                <tr>
                                    <td class="ml-2"><?= ++$sno1 ?></td>
                                    <td><?= $clerk['eino'] ?></td>
                                    <td><?= $clerk['cname'] ?></td>
                                    <td><?= $clerk['cfname'] ?></td>
                                    <td>
                                        <?php foreach ($model->getTransactions($clerk['id']) as $transaction) : ?>
                                            <?= $transaction['event_name'] ?> ON : <?= $transaction['formatted_event_date'] ?> <?= $transaction['remarks'] ?><br>
                                        <?php endforeach; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        
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
                "paging": true // Added to disable pagination

            }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');
        });
    </script>

    <div id="div_print">
        <div id="header" style="background-color:White;"></div>
        <div id="footer" style="background-color:White;"></div>
    </div>