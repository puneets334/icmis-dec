<?php
if (count($result_array) > 0) {
?>
<div id="prnnt" style="text-align: center;">
    <h3 style="text-align:center;">REGULAR STAGE CASES ARE UPDATED/LISTED IN MISC. HEAD</h3>
    <table id="customers" class="table table-striped custom-table">
        <thead>
            <tr>
                <th>SrNo.</th>
                <th>Case No. @ Diary No.</th>
                <th>Cause Title</th>
                <th>Tentative Date</th>
                <th>Section</th>
                <th>DA</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sno = 1;
            foreach ($result_array as $ro) {
                $sno1 = $sno % 2;
                if ($sno1 == '1') { ?>
                    <tr>
                    <?php } else { ?>
                    <tr>
                    <?php
                }
                    ?>
                    <td><?php echo $ro['SNO']; ?></td>
                    <td><?php echo $ro['REGNO_DNO']; ?></td>
                    <td><?php echo $ro['TITLE']; ?></td>
                    <td><?php echo $ro['Tentative_Date']; ?></td>
                    <td><?php echo $ro['SECTION'];  ?></td>
                    <td><?php echo $ro['DA']; ?></td>
                    </tr>
                <?php
                $sno++;
            }
                ?>
        </tbody>
    </table>
</div>
<input name="prnnt1" type="button" id="prnnt1" class="btn btn-primary quick-btn" value="Print">
<?php
} else {
    echo "No Recrods Found";
}
?>