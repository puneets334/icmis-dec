<?php if (count($data) > 0) { ?>
    <!-- <input name="prnnt1" type="button" id="prnnt1" value="Print"> -->
    <style>
      table.dataTable>thead .sorting,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }

    .dataTables_filter
    {
        margin-top: -45px;
    }
</style>
    <h3 align="center">Cases updated / fixed by user to list on <?php echo $list_dt; ?> </h3>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover" id="customers">
            <thead class="thead-dark">
                <tr>
                    <th width="5%">SrNo.</th>
                    <th width="15%">Case No. / Diary No.</th>
                    <th width="15%">Cause Title</th>
                    <th width="5%">Mainhead</th>
                    <th width="5%">Board Type</th>
                    <th width="10%">Fixed For</th>
                    <th width="10%">Coram</th>
                    <th width="10%">Category</th>
                    <th width="10%">Updated By</th>
                    <th width="10%">Entry Date/Time</th>
                    <th width="10%">Section/DA</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                foreach ($data as $ro) {
                ?>
                    <tr>
                        <td class="align-middle"><?php echo $sno; ?></td>
                        <td class="align-middle"><?php echo $ro['case_no']; ?></td>
                        <td class="align-middle"><?php echo $ro['cause_title']; ?></td>
                        <td class="align-middle">
                            <?php
                            if ($ro['mainhead'] == 'M') {
                                echo "Misc.";
                            }
                            if ($ro['mainhead'] == 'F') {
                                echo "Regular";
                            }
                            ?>
                        </td>
                        <td class="align-middle">
                            <?php
                            if ($ro['board_type'] == 'J') {
                                echo "Court";
                            }
                            if ($ro['board_type'] == 'C') {
                                echo "Chamber";
                            }
                            if ($ro['board_type'] == 'R') {
                                echo "Registrar";
                            }
                            ?>
                        </td>
                        <td class="align-middle"><?php echo $ro['fixed_for']; ?></td>
                        <td class="align-middle"><?php echo $ro['coram']; ?></td>
                        <td class="align-middle"><?php echo $ro['subject_category']; ?></td>
                        <td class="align-middle"><?php echo $ro['section_name_updated_by'] . "<br>" . $ro['username'] . " [" . $ro['empid'] . "]"; ?></td>
                        <td class="align-middle"><?php echo date('d-m-Y H:i:s', strtotime($ro['ent_dt'])); ?></td>
                        <td class="align-middle"><?php echo $ro['section_name'] . "<br>" . $ro['da_name']; ?></td>
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
    var reportTitle = "Cases updated / fixed by user to list on <?php echo $list_dt; ?>";
    $("#customers").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": [
            {
            extend: 'excelHtml5',
            title: reportTitle
            },
            {
            extend: 'print',
            title: reportTitle
            }
            ]
    });
</script>