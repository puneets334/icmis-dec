<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Pending Matters of
                                    <?php echo ltrim($heading, " and"); ?> as on <?= date('d/m/Y'); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <style>
                            table.dataTable>thead .sorting,
                            table.dataTable>thead {
                                background-color: #0d48be !important;
                                color: #fff !important;
                            }
                        </style>

                        <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">
                            <div id="printable">
                                <table id="query_builder_report" class="query_builder_report table table-bordered table-striped"> <!-- <table border="1" bgcolor="#FBFFFD"  id="mydt"  class="tbl_hr" width="98%" cellspacing="0" -->

                                    <thead>
                                        <tr>
                                            <th>Sr.No.</th>
                                            <th>Case No.</th>
                                            <th>Cause Title</th>
                                            <th>Dealing Assistant</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sno = 1;
                                        foreach ($details_list as $row) {
                                        ?>
                                            <tr>
                                                <td style="text-align: center;"><?php echo $sno ?></td>
                                                <td><?php echo $row['diary_no'] . "/" . $row['diary_year'] . "<br/>" .
                                                        $row['reg_no_display'] ?></td>
                                                <td><?php echo $row['pet_name'] ?><strong> Vs </strong><?= $row['res_name'] ?></td>
                                                <td><?php echo $row['name'] . "(" . $row['empid'] . ")/" . $row['section_name'] ?></td>

                                            </tr>
                                        <?php
                                            $sno++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
<script>
    $(function() {
        $("#query_builder_report").DataTable({
            "responsive": true,
            "lengthChange": false,
            "pageLength": 20,
            "autoWidth": false,
            "buttons": [
                "copy", 
                {
                    extend: "csv",
                    title: "Pending Matters of <?php echo ltrim($heading, ' and') . ' as on ' . date('d/m/Y'); ?>",
                },
                {
                    extend: "excel",
                    title: "Pending Matters of <?php echo ltrim($heading, ' and') . ' as on ' . date('d/m/Y'); ?>",
                }, {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    title: "Pending Matters of <?php echo ltrim($heading, ' and') . ' as on ' . date('d/m/Y'); ?>",
                },

            ],

            "bProcessing": true,
        }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

    });
</script>