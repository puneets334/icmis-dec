<div class="table-responsive">
<?php
if ($results) { ?>
    <table id="example1" class="table table-striped custom-table">
        <thead>
            <tr>
                <th>SNo.</th>
                <th>Diary No</th>
                <th>Diary Date</th>
                <th>Registration<br>Details</th>
                <th>IF Refiling</th>
                <th>Cause-Title</th>
                <th>Tentative Section / DA</th>
                <th>State-Agency</th>
                <th>Listing</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sno = 1;
        foreach ($results as $row) {
            $cause_title = $row['pet_name'];
            if (!empty($row['res_name'])) {
                $cause_title .= ' V/S ' . $row['res_name'];
            }

            $tentative_section = $row['section_name'];
            if (!empty($row['name'])) {
                $tentative_section .= ' // ' . $row['name'];
            }

            $state_agency = $row['agency_state'];
            if (!empty($row['agency_name'])) {
                $state_agency .= ' // ' . $row['agency_name'];
            }
        ?>
            <tr>
                <td><?php echo $sno; ?></td>
                <td><?php echo substr($row['diary_no'], 0, -4) . '/' . substr($row['diary_no'], -4); ?></td>
                <td><?php echo date('d-m-Y h:i:s A', strtotime($row['diary_no_rec_date'])); ?></td>
                <td><?php echo $row['short_description'] . substr($row['fil_no'], 3) . '/'; ?></td>
                <td><?php if ($row['rm_dt'] != '0000-00-00 00:00:00' && $row['rm_dt'] != NULL) echo date('d-m-Y h:i:s A', strtotime($row['rm_dt'])); ?></td>
                <td><?php echo $cause_title; ?></td>
                <td><?php echo $tentative_section; ?></td>
                <td><?php echo $state_agency; ?></td>
                <td><?php 
                    if ($row['next_dt'] >= date('Y-m-d') && $row['roster_id'] > 0 && $row['clno'] > 0 && $row['brd_slno'] > 0) {
                        echo date('d-m-Y', strtotime($row['next_dt'])) . " in ";
                        if ($row['board_type'] == 'J') {
                            echo "Hon. Coram";
                        } else if ($row['board_type'] == 'C') {
                            echo "Chamber";
                        } else if ($row['board_type'] == 'R') {
                            echo "Registrar";
                        }
                    } else {
                        echo "N";
                    } ?></td>
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
    <div class="mt-26 red-txt center">SORRY, NO RECORD FOUND!!!</div>
<?php
}?>
</div>
<script>
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": [],
        "searching": false
    });
</script>