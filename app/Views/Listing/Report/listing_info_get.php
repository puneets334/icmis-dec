<style>
.custom-table thead th{background:none;}
.custom-table thead th:first-child,.custom-table thead th:last-child,.custom-table tbody td:last-child
{border-radius: 0px;}
.custom-table thead th,.custom-table tbody td,.custom-table tbody tr th { 
    border-right: #999 1px solid;
}
.custom-table tbody td:first-child,.custom-table tbody tr th:first-child {
    border-left: #000 1px solid;
    border-radius: 0px;
}   
/* .custom-table tbody td:last-child,.custom-table tbody tr th:last-child {
    border-bottom: #000 1px solid;
}   */
.custom-table tbody tr:last-child th{
    border-bottom: #000 1px solid;

}
</style>
<!-- Print Button -->
<input name="prnnt1" type="button" id="prnnt1" class="btn btn-primary" value="Print">

<!-- Print Content -->
<div id="prnnt" style="text-align: center; font-size:14px;">
    <h3><?php echo $h3_head; ?></h3>
    <?php if (count($result_array) > 0) { ?>
        <table class="table table-striped table-bordered custom-table">
            <thead>
                <tr>
                    <th style="background: #918788;color: #dce38d;">SrNo.</th> 
                    <th style="background: #918788;color: #dce38d;">Coram</th>
                    <th style="background: #918788;color: #dce38d;">Pre-Allocation Availability</th>
                    <th style="background: #918788;color: #dce38d;">Total Allocated</th>
                    <th style="background: #918788;color: #dce38d;">After Allocation Excess Matter</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                foreach ($result_array as $ro) {
                ?>
                    <tr >
                        <td style="background: #ececec;"><?php echo $sno++; ?></td>
                        <td style="background: #ececec;">
                            <?php
                            if ($ro['coram'] == null) {
                                echo "TOTAL";
                                $total_excess = ($ro['total_allocated'] + $ro['total_after_allocation']) - $ro['total_pre_allocation'];
                            } else {
                                echo $ro['abbreviation'];
                            }
                            ?>
                        </td>
                        <td style="background: #ececec;"><?php echo $ro['total_pre_allocation']; ?></td>
                        <td style="background: #ececec;"><?php echo $ro['total_allocated']; ?></td>
                        <td style="background: #ececec;"><?php echo $ro['total_after_allocation']; ?></td>
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