<?php if (!empty($results)): ?>
    <table border="1">
        <tr>
            <th>Action</th>
            <th>S.No.</th>
            <th>Diary No.</th>
            <th>Parties</th>
            <th>Reason to Block</th>
            <th>Section</th>
            <th>Date</th>
        </tr>
        <?php $sno = 1; ?>
        <?php foreach ($results as $row): ?>
            <tr>
                <td>
                    <button type="button" id="btnDelete<?php echo $row['id']; ?>" value="Remove" class="caseBlckDelete btn btn-danger btn-sm"><i class="fas fa-trash" aria-hidden="true"></i></button>
                </td>
                <td><?= $sno + 1 ?></td>
                <td><?= substr($row['diary_no'], 0, -4) . '/' . substr($row['diary_no'], -4) ?></td>
                <td><?= ($row['pet_name'] != '' && $row['res_name'] != '') ? $row['pet_name'] . '<b> V/S </b>' . $row['res_name'] : '' ?></td>
                <td><?= $row['reason_blk'] ?></td>
                <td><?= $row['section_name'] ?></td>
                <td><?= date('d-m-Y h:i:s A', strtotime($row['ent_dt'])) ?></td>

            </tr>
            <?php $sno++; ?>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <div class="sorry">SORRY, NO RECORD FOUND!!!</div>
<?php endif; ?>