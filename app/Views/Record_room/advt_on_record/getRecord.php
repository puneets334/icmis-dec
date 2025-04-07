<div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">
    <div id="printable">
        <table id="query_builder_report" class="query_builder_report table table-bordered table-striped"> <!-- <table border="1" bgcolor="#FBFFFD"  id="mydt"  class="tbl_hr" width="98%" cellspacing="0" -->
            <thead>
                <tr>
                    <th>SNo</th>
                    <th width="25%">Case</th>
                    <th width="25%">Cause Title</th>
                    <th width="10%">Main/Connected</th>
                    <th width="20%">Status</th>
                    <th width="10%">Order/Judgment</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($getAdvtDetails) && !empty($getAdvtDetails)): ?>
                    <?php
                    $s_no = 1;
                    foreach ($getAdvtDetails as $case):
                    ?>
                        <tr>
                            <td><?php echo $s_no++; ?></td>
                            <td><?php echo htmlspecialchars($case['no']); ?><?php echo htmlspecialchars($case['diary_no']); ?></td>
                            <td><?php echo htmlspecialchars($case['causetitle']); ?></td>
                            <td><?php echo htmlspecialchars($case['main_connected']); ?></td>
                            <td><?php echo htmlspecialchars($case['status']); ?></td>
                            <td><?php echo ''; ?></td> <!-- Leave empty or add data if available -->
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No records found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
        $(function() {
            $("#query_builder_report").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 25,
                "buttons": [
                    "copy", "csv", {
                        extend: "excel",
                        title: "Report_<?= date("Y-m-d H:i:s");?>",
                    }, {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        title: "Report_<?= date("Y-m-d H:i:s");?>",
                       },  
                    // {
                    //     extend: 'colvis',
                    //     text: 'Show/Hide'
                    // }
                ],
                "processing": true, // Changed "bProcessing" to "processing"
                "ordering": true, // Added to disable sorting

            }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');
        });
    </script>

    <div id="div_print">
        <div id="header" style="background-color:White;"></div>
        <div id="footer" style="background-color:White;"></div>
    </div>