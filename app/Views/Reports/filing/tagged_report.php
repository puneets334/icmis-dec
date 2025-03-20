<!-- Main content -->
<?= view('header') ?>
<section class="content">
    <div class="container_">
        <div class="card">
           
            <?php
            if (isset($tagged_result) && sizeof($tagged_result) > 0) {
            ?>
                <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">
                    <div id="printable">
                        <h3>List of Pending matters where Main Case is Regular Hearing Matter<br />
                        or Subject Category 1900, 2000 and are connected with Fresh Matter</h3>
                        <table id="query_builder_report" class="table custom-table  table-bordered table-striped table-hover">
                            <thead>                                
                                <tr>
                                    <th>Sr.No.</th>
                                    <th>Main Case</th>
                                    <th>Connected Case</th>
                                    <th>Connected/Linked</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                foreach ($tagged_result as $result) 
                                {
                                    $i++;
                                ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo substr($result->main_case, 0, -4) . '/' . substr($result->main_case, -4); ?></td>
                                        <td><?php echo substr($result->connected_case, 0, -4) . '/' . substr($result->connected_case, -4); ?></td>
                                        <td><?php echo  $result->connected; ?></td>
                                    </tr>
                                    <?php
                                }
                            ?>
                            </tbody>
                        </table>
                   
                    </div>
                </div>
            <?php
            } else { ?>
                 <h3>List of Pending matters where Main Case is Regular Hearing Matter<br />
                 or Subject Category 1900, 2000 and are connected with Fresh Matter</h3>
                <table id="query_builder_report" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th colspan="4" class="text-center">
                                List of Pending matters where Main Case is Regular Hearing Matter<br />
                                or Subject Category 1900, 2000 and are connected with Fresh Matter
                            </th>
                        </tr>
                        <tr>
                            <th>Sr.No.</th>
                            <th>Main Case</th>
                            <th>Connected Case</th>
                            <th>Connected/Linked</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center">
                                No Record Found!
                            </td>
                        </tr>
                    </tbody>
                </table>

            <?php
            }
            ?>
        </div>
    </div>
    <div id="div_print">
        <div id="header" style="background-color:White;"></div>
        <div id="footer" style="background-color:White;"></div>
    </div>
</section>

<script>
    $(function() {
        $("#query_builder_report").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": true,
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