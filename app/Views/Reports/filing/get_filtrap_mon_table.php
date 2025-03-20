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
        "buttons": ["excel", "pdf"]
    });
</script>