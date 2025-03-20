<div class="card">

    <?php
    $category_display = "";
    $date_display = "";
    $heading = "";
    $status_display = "";
    $document_display = "";
    $source_display = "";

    if (isset($dataView) && sizeof($dataView) > 0) {


        switch ($category) {
            case 1: {
                $category_display = "Urgent Certified";
                break;
            }
            case 2: {
                $category_display = "Ordinary Urgent";
                break;
            }
            case 3: {
                $category_display = "Ordinary Certified";
                break;
            }
            case 4: {
                $category_display = "Ordinary";
                break;
            }
            default: {
                $category_display = " ";
                break;
            }
        }
        if ($status != '0') {
            foreach ($dataView as $result) {

                // echo "<pre>"; print_r($result);exit;
                $status_display = " and " . $result->status;
                $source_display = $result->description;
                // $document_display = $result->order_type;
            }
        } else {
            $status_display = "";
            $source_display = "";
            $document_display = "";
        }

        switch ($radiodate) {
            case 1: {
                $date_display = "Applications received";
                break;
            }
            case 2: {
                $date_display = "Application's Requisition sent to Judicial Section";
                break;
            }
            case 3: {
                $date_display = "Application's File received in Copying Section";
                break;
            }
            default: {
                $date_display = " ";
                break;
            }
        }

        if ($from_date == $to_date)
            $heading = "On " . date('d-m-Y', strtotime($from_date));
        else
            $heading = "from " . date('d-m-Y', strtotime($from_date)) . " to " . date('d-m-Y', strtotime($to_date));
        ?>
    <?php }
    ?>
    <div class="card-body">
        <tr>

            <?php $title = $category_display . " " . $date_display . " " . $heading . " " . $status_display;
            ?>
            <h1><?php echo $category_display . " " . $date_display . " " . $heading . " " . $status_display; ?> </h1>



            <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <?php if (!empty($dataView)) : ?>
                    <table id="CopyingView" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>SNo.</th>
                            <th>Application<br />Number</th>
                            <th>Diary<br />Number</th>
                            <th>Applied By</th>
                            <th>Applied On</th>
                            <th>Application <br /> Status</th>
                            <th>Court Fees</th>
                            <th>Source</th>
                            <th> Received By</th>
                            <th>Dealing Assistant</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sno = 1;
                        foreach ($dataView as $row) : ?>
                            <tr>
                                <td><?= $sno++ ?></td>
                                <td><a target="_blank" href="Report/documents?id=<?= $row->id ?>&num=<?= $row->application_number_display ?>"><?php echo $row->application_number_display ?></a></td>
                                <td><?= substr($row->diary, 0, -4) . '/' . substr($row->diary, -4); ?></td>

                                <td><?= $row->coian_name ?></td>
                                <td><?= isset($row->received_on) ? date('d-m-Y', strtotime($row->received_on)) : '' ?></td>
                                <td><?= $row->status ?></td>
                                <td><?= $row->court_fee ?></td>
                                <td><?= $row->description ?></td>
                                <td><?= $row->user . "(" . $row->empid . ")"; ?></td>
                                <td>
                                    <?php
                                    if ($row->da_section === 'No Section') {
                                        echo $row->da_name . " (" . $row->da_empid . ")/";
                                    } else {
                                        echo $row->da_name . " (" . $row->da_empid . ")/" . $row->da_section;
                                    }
                                    ?>
                                </td>

                            </tr>

                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        </tfoot>
                    </table>
                <?php else : ?>
                    <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
                <?php endif; ?>
                <!-- end of refiling search -->

            </div>
            <script>
                $(function() {
                    var title = '<?= $title ?>';

                    $("#CopyingView").DataTable({
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "buttons": ["copy", "csv", "excel", {
                            extend: 'pdfHtml5',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: title

                        }, {
                            extend: 'print',
                            title: title

                        },
                            {
                                extend: 'colvis',
                                text: 'Show/Hide',


                            }
                        ],
                        "bProcessing": true,
                        "extend": 'colvis',
                        "text": 'Show/Hide'
                    }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

                });
            </script>