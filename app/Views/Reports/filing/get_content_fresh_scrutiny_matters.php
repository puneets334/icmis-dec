<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php  if(!empty($ReportsFreshScrutinyMatters)):?>
                <table  id="ReportsFreshScrutinyMatters" class="table table-bordered table-striped">
                    <thead>
                    <tr  class="inner-wrap">
                        <th>Sr.No.</th>
                        <th>Name</th>
                        <th>Emp. No.</th>
                        <th>Designation</th>
                        <th>Total Matters</th>
                        <th>Completed</th>
                        <th>Pending</th>
                    </tr>
                    </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($ReportsFreshScrutinyMatters as $row):
                        ?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= $row->name ?></td>
                            <td><?= $row->empid ?></td>
                            <td><?= $row->type_name ?></td>
                            <td><?= $row->total ?></td>
                            <td><?= $row->completed ?></td>
                            <td><?= $row->pending ?></td>
                           
                        </tr>

                    <?php endforeach; ?>
                    </tbody><tfoot>
                    <tr>


                        <th colspan="4" class="text-right text-bold">Total Fresh Matters</th>
                        <th></th>
                        <th></th>
                        <th></th>

                    </tr>
                    </tfoot>
                </table>
            <?php else : ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php endif; ?>
            <!-- end of refiling search -->

        </div>
        <script>
            $(function () {
                $("#ReportsFreshScrutinyMatters").DataTable({
                    "footerCallback": function (row, data, start, end, display) {
                        let api = this.api();

                        // Remove the formatting to get integer data for summation
                        let intVal = function (i) {
                            return typeof i === 'string'
                                ? i.replace(/[\$,]/g, '') * 1
                                : typeof i === 'number'
                                    ? i
                                    : 0;
                        };

                        // Total over all pages
                        total4c = api
                            .column(4)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);
                        total5c = api
                            .column(5)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);
                        total6c = api
                            .column(6)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        // Total over this page
                        pageTotal4c = api
                            .column(4, { page: 'current' })
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);
                        pageTotal5c = api
                            .column(5, { page: 'current' })
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);
                        pageTotal6c = api
                            .column(6, { page: 'current' })
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        // Update footer
                        api.column(4).footer().innerHTML =
                            '' + pageTotal4c + ' ( ' + total4c + ' total)';
                        api.column(5).footer().innerHTML =
                            '' + pageTotal5c + ' ( ' + total5c + ' total)';
                        api.column(6).footer().innerHTML =
                            '' + pageTotal6c + ' ( ' + total6c + ' total)';
                    },
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');


            });

        </script>
