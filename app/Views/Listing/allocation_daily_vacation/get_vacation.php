<div class="card-body">
    <div class="d-flex justify-content-start mb-3">
        <button type="button" class="btn btn-primary" id="bsubmit" onclick="addRecord()">Submit</button>
    </div>
    <div id="prnnt" style="font-size:12px;">
        <h3><?= $mainhead == 'M' ? "Misc. Hearing" : "Regular Hearing"; ?></h3>
        <div class="table-responsive">
            <?php if ($records) { ?>
                <table id="example1" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th scope="col" width="5%">SNo.</th>
                            <th scope="col" width="25%">Diary/Reg No</th>
                            <th scope="col" width="25%">Petitioner / Respondent</th>
                            <th scope="col" width="15%">Last Order</th>
                            <th scope="col" width="12%">Remark</th>
                            <th scope="col" width="13%">Section/ DA</th>
                            <th scope="col" width="5%">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="chkall2" id="chkall2" value="ALL" onclick="all_case_v(this);">
                                    <label class="form-check-label" for="chkall2">All</label>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sno = 1;
                        foreach ($records as $record) {
                        ?>
                            <tr>
                                <th scope="row"><?= $sno++; ?></th>
                                <td><?= substr_replace($record['diary_no'], '-', -4, 0) . "<br>" . $record['reg_no_display']; ?></td>
                                <td><?= $record['pet_name'] . "<br/>Vs<br/>" . $record['res_name']; ?></td>
                                <td><?= $record['lastorder']; ?></td>
                                <td><?= $record['purpose']; ?></td>
                                <td><?= $record['sec'] . "<br>" . $record['name']; ?></td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="chkeeed2" name="chk2" value="<?= $record['diary_no'] . "@" . $record['submaster_id']; ?>">
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="mt-3 text-danger text-center">SORRY, NO RECORD FOUND!!!</div>
            <?php } ?>
        </div>
    </div>
    <button type="button" class="btn btn-primary mt-3" id="prnnt1">Print</button>
</div>

<script>
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "buttons": [],
        "searching": false
    });
</script>