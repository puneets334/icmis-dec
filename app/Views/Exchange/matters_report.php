<?php if (count($records) > 0) { ?>
    <table id="reportTable1" class="table" style="width: 100%">
        <thead>
            <tr>
                <th style="width:8%;"><b>Listing Date</b></th>
                <th style="width:20%;"><b>Listed</b></th>
                <th style="width:8%;"><b>Received</b></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $last_date = '';
            foreach ($records as $data) {
                $curr_date = $data['listing_date'];
            ?>
                <tr>
                    <?php
                    if ($curr_date == $last_date) { ?>
                        <td>&nbsp</td>

                    <?php } else {
                    ?>

                        <td><?= date("d-m-Y", strtotime($data['listing_date'])) ?></td>
                    <?php }
                    ?>

                    <td><?= $data['listed'] ?></td>
                    <td><?= $data['received'] ?></td>
                </tr>
            <?php
                $last_date = $curr_date;
            } ?>
        </tbody>
    </table>
<?php
} else {
    echo "No Record Found!!";
} ?>