<?php
if (!empty($result) > 0) {
?>
    <div class="table-responsive">
        <table id="get_filtrap_mon" class="table table-striped custom-table">
            <thead>
                <tr>
                    <th>SNo.</th>
                    <th>User</th>
                    <th>Dispatched</th>
                    <th>Completed</th>
                    <th>Total Pending</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                foreach ($result as $row) {
                ?>
                    <tr>
                        <td><?php echo $sno; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['sent']; ?></td>
                        <td><?php echo $row['comp']; ?></td>
                        <td><?php echo $row['pending']; ?></td>
                    </tr>
                <?php
                    $sno++;
                }
                ?>
            </tbody>
        </table>
    </div>
<?php
} else {
?>
    <div style="text-align: center;font-size: 17px;color: red">SORRY, NO RECORD FOUND!!!</div>
<?php
}
?>
<script>
    $("#get_filtrap_mon").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": [{
                extend: "copy",
                title: "CONSOLIDATED FILING-TRAP REPORT FOR\n(As on <?php echo date('d-m-Y'); ?>)"
            },
            {
                extend: "csv",
                title: "CONSOLIDATED FILING-TRAP REPORT FOR\n(As on <?php echo date('d-m-Y'); ?>)"
            },
            {
                extend: "excel",
                title: "CONSOLIDATED FILING-TRAP REPORT FOR\n(As on <?php echo date('d-m-Y'); ?>)"
            },
            {
                extend: "pdfHtml5",
                title: "CONSOLIDATED FILING-TRAP REPORT FOR\n(As on <?php echo date('d-m-Y'); ?>)",
                customize: function(doc) {
                    doc.content.splice(0, 0, {
                        text: "CONSOLIDATED FILING-TRAP REPORT FOR\n(As on <?php echo date('d-m-Y'); ?>)",
                        fontSize: 12,
                        alignment: "center",
                        margin: [0, 0, 0, 12]
                    });
                }
            },
            {
                extend: "print",
                title: "",
                messageTop: "<h3 style='text-align:center;'>ADVOCATE ON RECORD NOT GO BEFORE JUDGE<br>(As on <?php echo date('d-m-Y'); ?>)</h3>"
            }
        ]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
</script>