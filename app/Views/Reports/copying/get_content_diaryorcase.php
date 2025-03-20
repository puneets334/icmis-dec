<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php
            $title = $report_title;

                ?>
                <table  id="ReportRefiling" class="table table-bordered table-striped">
                    <thead>
                    <h3 style="text-align: center;"><?=$title?></h3>
                    <?php if(!empty($resultDiaryorcase)):?>
                    <tr>
                        <th>SNo.</th>
                        <th>Application Number</th>
                        <th>Applied By</th>
                        <th>Applied On</th>
                        <th>Application Status</th>
                        <th>Court Fees</th>

                    </tr>
                    </thead><tbody>
                    <?php
                    $sno = 1;
                     foreach($resultDiaryorcase as $row):?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= $row->application_number_display ?></td>
                            <td><?= $row->name ?></td>
                            <td><?=isset($row->received_on) ? date('d-m-Y',strtotime($row->received_on)) : ''?></td>
                            <td><?= $row->status ?></td>
                            <td><?= $row->court_fee ?></td>

                           </tr>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    </tfoot>
                </table>
            <?php else : ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Application has been applied against the given details</div>
            <?php endif; ?>
            <!-- end of refiling search -->

        </div>
        <script>
            $(function () {
                var title = '<?=$title?>';
                $("#ReportRefiling").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL', title: title },{extend: 'print', title: title },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });

        </script>
