<br>
<br>
<!-- Print Button -->
<input name="prnnt1" type="button" id="prnnt1" class="btn btn-primary" value="Print">

<!-- Print Content -->
<div id="prnnt" style="text-align: center; font-size:14px;">
    <h3><?php echo $h3_head; ?></h3>
    <?php if (count($result_array) > 0) { ?>
        <table class="table table-striped table-bordered custom-table">
            <thead>
                <tr>
                    <th>SrNo.</th> 
                    <th>Coram</th>
                    <th>Pre-Allocation Availability</th>
                    <th>Total Allocated</th>
                    <th>After Allocation Excess Matter</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                foreach ($result_array as $ro) {
                ?>
                    <tr style="background: #ececec;">
                        <td><?php echo $sno++; ?></td>
                        <td>
                            <?php
                            if ($ro['coram'] == null) {
                                echo "TOTAL";
                                $total_excess = ($ro['total_allocated'] + $ro['total_after_allocation']) - $ro['total_pre_allocation'];
                            } else {
                                echo $ro['abbreviation'];
                            }
                            ?>
                        </td>
                        <td><?php echo $ro['total_pre_allocation']; ?></td>
                        <td><?php echo $ro['total_allocated']; ?></td>
                        <td><?php echo $ro['total_after_allocation']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Note about total excess -->
        <p class="mt-3">Note: Total <?php echo $total_excess; ?> cases updated between cause list allocation and publication.</p>
    <?php
    } else {
        echo "<p>No Records Found</p>";
    }
    ?>
</div>