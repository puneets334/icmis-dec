<div class="container text-center mt-4 " id="prnnt">
    <h3>Category Allocated Report for Dated <?php echo $list_dt; ?> (<?php echo $mainhead_descri; ?>)</h3>
    <?php if (count($result_array) > 0) { ?>
        <table class="table table-bordered">
            <thead class="bg-secondary text-light">
                <tr>
                    <th style="width: 10%; color:black;">SNo.</th>
                    <th style="width: 45%; color:black;">Category</th>
                    <th style="width: 45%; color:black;">Hon'ble Judges</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                foreach ($result_array as $row) {
                   // $rowClass = ($sno % 2 === 1) ? 'bg-light' : 'bg-pink'; // Change this to a class or color for the second color
                ?>
                    <tr class="<?php //echo $rowClass; ?>">
                        <td><?php echo $sno; ?></td>
                        <td><?php echo $row['sub_name1']; ?></td>
                        <td class="text-left">
                            <?php
                            // You can add the logic to display judges here if needed
                            ?>
                        </td>
                    </tr>
                <?php
                    $sno++;
                }
                ?>
            </tbody>
        </table>
    <?php } else {
        echo "<div class='alert alert-warning'>No Records Found</div>";
    } ?>
    <br><br><br><br>
</div>
<div class="fixed-bottom bg-light text-center border-top">
    <button class="btn btn-primary" id="prnnt1">Print</button>
</div>
