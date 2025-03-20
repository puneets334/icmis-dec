<?php
if (!empty($result) > 0) {

?>
    <div class="table-responsive">
        <table id="example1" class="table table-striped custom-table">
            <thead>
                <tr>
                    <th>SNo.</th>
                    <th>Diary No.</th>
                    <th>Dispatch By</th>
                    <th>Dispatch On</th>
                    <th>Remarks</th>
                    <th>Dispatch To</th>
                    <th>Receive By</th>
                    <th>Receive On</th>
                    <th>Completed On</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                foreach ($result as $row) {
                ?>
                    <tr>
                        <td><?php echo $sno; ?></td>
                        <td><?php echo substr($row['diary_no'], 0, -4) . '/' . substr($row['diary_no'], -4); ?></td>
                        <td><?php echo $row['d_by_name']; ?></td>
                        <td><?php
                            if ($row['disp_dt'] != '0000-00-00 00:00:00')
                                echo date('d-m-Y h:i:s A', strtotime($row['disp_dt'])); ?></td>
                        <td><?php echo $row['remarks']; ?></td>
                        <td><?php echo $row['d_to_name']; ?> [<?php echo $row['d_to_empid']; ?>]</td>
                        <td><?php echo $row['r_by_name']; ?> [<?php echo $row['r_by_empid']; ?>]</td>
                        <td><?php
                            if (!empty($row['rece_dt']))
                                echo date('d-m-Y h:i:s A', strtotime($row['rece_dt'])); ?></td>
                        <td><?php
                            if (!empty($row['comp_dt']))
                                echo date('d-m-Y h:i:s A', strtotime($row['comp_dt']));
                            if ($row['other'] != 0) {
                                echo '<br> ' . $row['o_name'];
                            }
                            ?></td>
                    </tr>
                <?php
                    $sno++;
                }
                ?>
            </tbody>

        </table>
    <?php
} else {
    ?>
        <div class="nofound">SORRY NO RECORD FOUND</div>
    <?php
}?>
<script>
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": ["excel", "pdf"]
    });
</script>