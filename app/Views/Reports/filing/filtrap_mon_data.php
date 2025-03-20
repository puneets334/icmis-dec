<div class="table-responsive mt-5">
    <?php //if (isset($trapData)) {
    ?>
        <table class="table table-striped custom-table" id="reportTable1">
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
                foreach ($trapData as $row) {
                ?>
                    <tr>
                        <th><?php echo $sno; ?></th>
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
    <?php
    // } else {
    ?>
        <!-- <div style="text-align: center;font-size: 17px;color: red">SORRY, NO RECORD FOUND!!!</div> -->
    <?php
    // }
    ?>
    </table>
</div>