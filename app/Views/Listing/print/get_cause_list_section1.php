<h3 class="text-center my-4">Cause List for Dated <?= $list_dt ?> (<?= $mainhead_descri ?>)</h3>

<?php if (!empty($cause_list)): ?>
    <div class="text-right mb-3">
        <button class="btn btn-primary" onclick="exportTableToExcel('tblData')">Export Table Data To Excel File</button>
    </div>
    <div class="table-responsive">
        <table id="tblData" class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Sr No.</th>
                    <th>Court No.</th>
                    <th>Item No.</th>
                    <th>Diary No</th>
                    <th>Reg No.</th>
                    <th>Petitioner / Respondent</th>
                    <th>Advocate</th>
                    <th>DA Name</th>
                    <th>Purpose</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cause_list as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $row['courtno'] ?></td>
                        <td><?= $row['itemno'] ?></td>
                        <td><?= $row['diaryno'] ?></td>
                        <td><?= $row['regno'] ?></td>
                        <td><?= $row['petitioner_respondent'] ?> </td>
                        <td><?= $row['advocate'] ?></td>
                        <td><?= $row['daname'] ?></td>
                        <td><?= $row['purpose'] ?></td>
                        <td><?= $row['remarks'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="text-center">No Records Found</p>
<?php endif; ?>
