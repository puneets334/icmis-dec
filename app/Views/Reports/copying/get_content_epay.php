<div class="card">
    <div class="card-body">
        <?php $application_number_display='';?>
        <?php foreach ($ordertype as $order)

            $application_number_display = $order->application_number_display;
        ?>

        <?php
        $title = "eCopying Payment Report : ";
      
      
        if ($rdbtn_select == 'CRN') {
            $title .= " Received against CRN - " . $crn;
        } else {
            $title .= " received date between " . $from_date . " and " . $to_date;
        }

        ?>
        <?php echo $title  ?>
        <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">
            <?php if (!empty($dataEpay)) : ?>
                <table id="query_builder_report" class="query_builder_report table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>SNo. </th>
                            <th>CRN</th>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Amount Paid</th>


                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sno = 1;
                        $rowTotal = 0;
                        $grandTotal = 0;
                        foreach ($dataEpay as $row) :
                            $grandTotal = $grandTotal + $row->total_sum;
                            // $sql2 = "select application_number_display from copying_order_issuing_application_new where crn = '" . $row->order_code . "'";

                            // $query = $this->db->query($sql2, array($row->order_code));
                            // $result = $query->row();

                            // print_r($result);exit;


                        ?>
                            <tr>
                                <td><?= $sno++ ?></td>
                                <td><?= $row->order_code ?></td>
                                <td><?= date("d-m-Y H:i:s", strtotime($row->entry_time)); ?></td>
                                <td><?= $row->shipping_first_name ?> <?= $row->shipping_last_name ?><br><?= $application_number_display ?></td>
                                <td><?= $row->description ?></td>
                                <td><?= $row->total_sum ?></td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-right text-bold">Total </th>
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
            $(function() {
                $("#query_builder_report").DataTable({
                    "footerCallback": function(row, data, start, end, display) {
                        let api = this.api();
                        // Remove the formatting to get integer data for summation
                        let intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i :
                                0;
                        };
                        // Total over all pages
                        total5c = api
                            .column(5)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        // Total over this page
                        pageTotal5c = api
                            .column(5, {
                                page: 'current'
                            })
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        // Update footer

                        api.column(5).footer().innerHTML = '' + pageTotal5c + ' ( ' + total5c + ' total)';
                    },
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