<?php if (count($res) > 0) { ?>
    <h3 style="text-align:center;">Pendency - Category Wise as on <?= $tdt1; ?> (Morning)</h3>
    <div class="table-responsive">
        <table class="table table-striped custom-table" id="diaryReport">
            <thead>
                <tr style="background: #A9A9A9; text-align: center;">
                    <th rowspan="2" style="font-weight: bold;">SrNo.</th>
                    <th rowspan="2" style="font-weight: bold;">Category Code</th>
                    <th rowspan="2" style="font-weight: bold;">Category Name</th>
                    <th colspan="3" style="font-weight: bold;">Total</th>
                    <th colspan="3" style="font-weight: bold;">Misc.</th>
                    <th colspan="3" style="font-weight: bold;">Regular</th>
                </tr>
                <tr style="background: #A9A9A9;">
                    <th style="font-weight: bold;">Main</th>
                    <th style="font-weight: bold;">Connected</th>
                    <th style="font-weight: bold;">Total</th>
                    <th style="font-weight: bold;">Main</th>
                    <th style="font-weight: bold;">Connected</th>
                    <th style="font-weight: bold;">Total</th>
                    <th style="font-weight: bold;">Main</th>
                    <th style="font-weight: bold;">Connected</th>
                    <th style="font-weight: bold;">Total</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $sno = 1;

                $grand_total = [
                    'main' => 0,
                    'conn' => 0,
                    'pendency' => 0,
                    'misc_main' => 0,
                    'misc_conn' => 0,
                    'misc' => 0,
                    'regular_main' => 0,
                    'regular_conn' => 0,
                    'regular' => 0,
                ];

                foreach ($res as $ro) {

                    if ($ro['subcode1'] === null || $ro['sub_name1'] === "TOTAL") {
                        continue;
                    }

                    $grand_total['main'] += (int)$ro['main'];
                    $grand_total['conn'] += (int)$ro['conn'];
                    $grand_total['pendency'] += (int)$ro['pendency'];
                    $grand_total['misc_main'] += (int)$ro['misc_main'];
                    $grand_total['misc_conn'] += (int)$ro['misc_conn'];
                    $grand_total['misc'] += (int)$ro['misc'];
                    $grand_total['regular_main'] += (int)$ro['regular_main'];
                    $grand_total['regular_conn'] += (int)$ro['regular_conn'];
                    $grand_total['regular'] += (int)$ro['regular'];

                    // Render Regular Rows
                ?>
                    <tr>
                        <td><?= $sno++; ?></td>
                        <td><?= ($ro['subcode1'] == 999) ? '0' : $ro['subcode1'] . '00'; ?></td>
                        <td><?= $ro['sub_name1']; ?></td>
                        <td><?= $ro['main']; ?></td>
                        <td><?= $ro['conn']; ?></td>
                        <td><?= $ro['pendency']; ?></td>
                        <td><?= $ro['misc_main']; ?></td>
                        <td><?= $ro['misc_conn']; ?></td>
                        <td><?= $ro['misc']; ?></td>
                        <td><?= $ro['regular_main']; ?></td>
                        <td><?= $ro['regular_conn']; ?></td>
                        <td><?= $ro['regular']; ?></td>
                    </tr>
                <?php } ?>

                <!-- Render Grand Total Row -->
                <tr style="font-weight: bold; background: #D3D3D3;">
                    <td colspan="3" align="left">TOTAL</td>
                    <td style="font-weight: bold; background: #D3D3D3;"><?= $grand_total['main']; ?></td>
                    <td style="font-weight: bold; background: #D3D3D3;"><?= $grand_total['conn']; ?></td>
                    <td style="font-weight: bold; background: #D3D3D3;"><?= $grand_total['pendency']; ?></td>
                    <td style="font-weight: bold; background: #D3D3D3;"><?= $grand_total['misc_main']; ?></td>
                    <td style="font-weight: bold; background: #D3D3D3;"><?= $grand_total['misc_conn']; ?></td>
                    <td style="font-weight: bold; background: #D3D3D3;"><?= $grand_total['misc']; ?></td>
                    <td style="font-weight: bold; background: #D3D3D3;"><?= $grand_total['regular_main']; ?></td>
                    <td style="font-weight: bold; background: #D3D3D3;"><?= $grand_total['regular_conn']; ?></td>
                    <td style="font-weight: bold; background: #D3D3D3;"><?= $grand_total['regular']; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
<?php
} else {
    echo "Records Not Found";
}
?>

<script>
    $(document).ready(function() {
        $("#diaryReport").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "dom": 'Bfrtip',
            "buttons": [
                'excel', 'pdf', 'print'
            ]
        });
    });
</script>