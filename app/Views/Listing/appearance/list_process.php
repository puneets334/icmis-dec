<?php $uri = current_url(true); ?>

<?php

//$title = "APPEARANCE REPORT FOR CAUSE LIST DATE " . $list_date_ymd . ' COURT NO. ' . $courtno . " (As on " . date('d-m-Y H:i:s') . ")";

$title = "APPEARANCE REPORT FOR CAUSE LIST DATE ".$list_date_ymd.' COURT NO. '. ($courtno == '21' ? 'Registrar Court' : $courtno) ." (As on ".date('d-m-Y H:i:s').")";

if (count($result) > 0) {
?>

    <div class="table-responsive">
        <h2 style="text-align: center;"><?= $title ?></h2>
        <table class="table table-striped custom-table" id="tab">
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
                    // pr($row);
                ?>
                    <tr>

                        <td> <?= $row['item_no']; ?></td>
                        <td><?= $row['case_no']; ?></td>
                        <td><?= $row['cause_title']; ?></td>
                        <td><?php
                            if (!empty($row['advocates'][0]['advocates'])) {
                                $advocate_html = '<ul class="advocate_ul">';
                                foreach ($row['advocates'][0]['advocates'] as $advocate) {
                                    $advocate_html .= '<li style="padding: 4px 0;">' . $advocate . '</li>';
                                }
                                $advocate_html .= '</ul>';
                                echo $advocate_html;
                            } else {
                                echo '-';
                            }

                            ?></td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
    </div>

<?php
} else {
    echo "<spam class='text-center text-danger'>No Records Found</spam>";
}
?>
<script>
    $(document).ready(function() {
        $("#tab").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "dom": 'Bfrtip',
            "bProcessing": true,
            "buttons": [{
                    extend: "excel",
                    text: "Export Excel",
                    pageSize: 'LEGAL',
                    orientation: 'landscape',
                },
                {
                    extend: "pdf",
                    text: "Export PDF",
                    pageSize: 'LEGAL',
                    orientation: 'landscape',
                    exportOptions: {
                        columns: ':visible' // Ensures all visible columns are exported
                    },
                    customize: function(doc) {
                        doc.styles.tableHeader.alignment = 'center'; // Center headers
                        doc.styles.tableBodyEven.alignment = 'center'; // Center table data
                        doc.styles.tableBodyOdd.alignment = 'center';

                        // Fix Bench Data Alignment
                        doc.content[1].table.body.forEach(function(row) {
                            row[4].alignment = 'left'; // Ensures Bench data aligns correctly
                            row[4].margin = [5, 5, 5, 5]; // Adds padding to prevent text cutting
                        });

                        // Increase font size for readability
                        doc.defaultStyle.fontSize = 10;
                        doc.styles.tableHeader.fontSize = 12;
                    }
                }
            ],
            "columnDefs": [{
                    "orderable": false,
                    "targets": -1
                } // Disable sorting on last column
            ]
        });
    });
</script>