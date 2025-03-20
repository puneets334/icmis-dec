<h3>Cases Listed by Update Heardt Module<?php echo $string_heading; ?> </h3>

<?php
if (count($data) > 0) {
?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover" id="customers">
            <thead class="thead-dark">
                <tr>
                    <th width="5%">Sr No.</th>
                    <th width="15%">Case No. / Diary No.</th>
                    <th width="15%">Cause Title</th>
                    <th width="10%">Cause List Date</th>
                    <th width="5%">Mainhead</th>
                    <th width="5%">Board Type</th>
                    <th width="5%">Item No.</th>
                    <th width="10%">Listed Before</th>
                    <?php if ($datetype == 2) { ?>
                        <th width="15%">Reason</th>
                    <?php } ?>
                    <th width="15%">Category</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                foreach ($data as $ro) {
                ?>
                    <tr>
                        <td><?php echo $sno; ?></td>
                        <td><?php echo $ro['case_no']; ?></td>
                        <td><?php echo $ro['cause_title']; ?></td>
                        <td><?php echo $ro['listed_on']; ?></td>
                        <td><?php
                            if ($ro['mf'] == 'M') {
                                echo "Misc.";
                            }
                            if ($ro['board_type_mb'] == 'F') {
                                echo "Regular";
                            }
                            ?></td>
                        <td><?php
                            if ($ro['board_type_mb'] == 'J') {
                                echo "Court";
                            }
                            if ($ro['board_type_mb'] == 'C') {
                                echo "Chamber";
                            }
                            if ($ro['board_type_mb'] == 'CC') {
                                echo "Review & Curative";
                            }
                            if ($ro['board_type_mb'] == 'R') {
                                echo "Registrar";
                            }
                            ?></td>
                        <td><?php echo $ro['item_no']; ?></td>
                        <td><?php echo $ro['listed_before']; ?></td>
                        <?php if ($datetype == 2) { ?>
                            <td><?php echo $ro['reason']; ?></td>
                        <?php } ?>
                        <td><?php echo $ro['subject_category']; ?></td>
                    </tr>
                <?php
                    $sno++;
                }
                ?>
            </tbody>
        </table>
    </div>
<?php
} else {
    echo "<p>No Records Found</p>";
}
?>

<script>
    $("#customers").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": ["excel", "pdf"]
    });
</script>