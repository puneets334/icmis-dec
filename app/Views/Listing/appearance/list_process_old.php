<?php $uri = current_url(true); ?>

<?php

$title = "APPEARANCE REPORT FOR CAUSE LIST DATE " . $list_date_ymd . ' COURT NO. ' . $courtno . " (As on " . date('d-m-Y H:i:s') . ")";

if (count($result) > 0) {
?>
    <!DOCTYPE html>
    <html>

    <head>
        <script>
            $(document).ready(function() {
                var filename = '<?= $title ?>';
                var title = '<?= $title ?>';

                $('#tab').DataTable({
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'excel',
                            className: 'btn btn-primary glyphicon glyphicon-list-alt',
                            filename: filename,
                            title: title,
                            text: 'Export to Excel',
                            autoFilter: true,
                            sheetName: 'Sheet1'
                        },
                        {
                            extend: 'pdf',
                            className: 'btn btn-primary glyphicon glyphicon-file',
                            filename: filename,
                            title: title,
                            pageSize: 'A4',
                            orientation: 'landscape',
                            text: 'Save as Pdf',
                            customize: function(doc) {
                                doc.styles.title = {
                                    fontSize: '18',
                                    alignment: 'left'
                                }
                            }
                        },
                        {
                            extend: 'print',
                            className: 'btn btn-primary glyphicon glyphicon-print',
                            title: title,
                            pageSize: 'A4',
                            orientation: 'portrait',
                            text: 'Print',
                            autoWidth: false,
                            columnDefs: [{
                                "width": "20px",
                                "targets": [0]
                            }],
                            customize: function(win) {
                                $(win.document.body).find('h1').css('font-size', '20px');
                                $(win.document.body).find('h1').css('text-align', 'left');
                                $(win.document.body).find('tab').css('width', 'auto');
                            }
                        }
                    ],
                    paging: true,
                    ordering: true,
                    info: true,
                    searching: true,
                    responsive: true,
                    lengthChange: true,
                    autoWidth: true,
                    bProcessing: true
                });
            });
        </script>
    </head>

    <body>


        <div class="table-responsive">
            <h2><?= $title ?></h2>
            <table class="table table_print table-striped custom-table" id="tab">
                <thead>
                    <tr>
                        <th>
                            Item No.
                        </th>
                        <th>
                            Case No.
                        </th>
                        <th>
                            Cause Title
                        </th>
                        <th>
                            Name of Advocates
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result as $row) {
                    ?>
                        <tr>

                            <td> <?= $row['item_no']; ?></td>
                            <?php

                            $diary = $CourtMaster->getDiaryDetails($row['diary_no']);

                            // Validate and process petitioner name
                            if (!empty($diary['pet_name'])) {
                                if ($diary['pno'] == 2) {
                                    $pet_name = $diary['pet_name'] . " AND ANR.";
                                } elseif ($diary['pno'] > 2) {
                                    $pet_name = $diary['pet_name'] . " AND ORS.";
                                } else {
                                    $pet_name = $diary['pet_name'];
                                }
                            } else {
                                $pet_name = ""; // Handle the case when pet_name is empty
                            }

                            // Validate and process respondent name
                            if (!empty($diary['res_name'])) {
                                if ($diary['rno'] == 2) {
                                    $res_name = $diary['res_name'] . " AND ANR.";
                                } elseif ($diary['rno'] > 2) {
                                    $res_name = $diary['res_name'] . " AND ORS.";
                                } else {
                                    $res_name = $diary['res_name'];
                                }
                            } else {
                                $res_name = ""; // Handle the case when res_name is empty
                            }
                            if (!empty($diary['reg_no_display'])) {
                            ?>

                                <td><?= $diary['reg_no_display']; ?></td>
                            <?php } else { ?>

                                <td> Diary No. <?= $row['diary_no']; ?></td>

                            <?php } ?>
                            <td><?= $pet_name ?><br>
                                Vs.<br>
                                <?= $res_name ?></td>
                            <td><?php echo $row['advocate_title'] . ' ' . $row['advocate_name'] . ', ' . $row['advocate_type']; ?></td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
    </body>

    </html>




<?php
} else {
    echo "<spam class='text-center text-danger'>No Records Found</spam>";
}
?>