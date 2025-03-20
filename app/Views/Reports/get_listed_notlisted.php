<div class="container mt-4">
    <h4 class="text-center">Listed Not Listed</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th rowspan="2">SNo.</th>
                    <th rowspan="2">Head</th>
                    <th colspan="3" class="text-center">Fixed Cases</th>
                    <th colspan="3" class="text-center">Computer Generated</th>
                    <th rowspan="2">Total Cases</th>
                </tr>
                <tr>
                    <th>Listed</th>
                    <th>Not Listed</th>
                    <th>Total</th>
                    <th>Listed</th>
                    <th>Not Listed</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $j = 1;
                $aff_rec = count($get_result);
                if (isset($get_result)) {
                    foreach ($get_result as $row) {
                        if ($row['subhead'] == '9999') $stagename = "Wrong Updation";
                        elseif ($row['subhead'] == '0') $stagename = "Blank entry";
                        else $stagename = $row['stagename'];
                ?>
                        <tr>
                            <?php if ($j == $aff_rec) { ?>
                                <td colspan="2">Total</td>
                            <?php } else { ?>
                                <td class="text-right"><?php echo $j; ?></td>
                                <td><?php echo $stagename; ?></td>
                            <?php } ?>
                            <td class="text-right"><?php echo $row['listed_fixed']; ?></td>
                            <td class="text-right"><?php echo $row['not_listed_fixed']; ?></td>
                            <td class="text-right text-danger"><?php echo $row['fixed']; ?></td>

                            <td class="text-right"><?php echo $row['listed_not_fixed']; ?></td>
                            <td class="text-right"><?php echo $row['not_listed_not_fixed']; ?></td>
                            <td class="text-right text-danger"><?php echo $row['not_fixed']; ?></td>

                            <td class="text-right text-danger"><?php echo ($row['fixed'] + $row['not_fixed']); ?></td>
                        </tr>
                <?php
                        $j++;
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>