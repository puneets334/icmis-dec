<table id="tblCasesForUploading" class="table table-striped table-hover">
    <thead>
        <tr>
            <th>SNo</th>
            <th width="25%">Case</th>
            <th width="25%">Cause Title</th>
            <th width="10%">Main/Connected</th>
            <th width="20%">Status</th>
            <th width="10%">Order/Judgment</th>
        </tr>
    </thead>
    <tbody>
        <?php if (isset($getAdvtDetails) && !empty($getAdvtDetails)): ?>
            <?php
            $s_no = 1;
            foreach ($getAdvtDetails as $case):
            ?>
            <tr>
                <td><?php echo $s_no++; ?></td>
                <td><?php echo htmlspecialchars($case['no']); ?><?php echo htmlspecialchars($case['diary_no']); ?></td>
                <td><?php echo htmlspecialchars($case['causetitle']); ?></td>
                <td><?php echo htmlspecialchars($case['main_connected']); ?></td>
                <td><?php echo htmlspecialchars($case['status']); ?></td>
                <td><?php echo ''; ?></td> <!-- Leave empty or add data if available -->
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No records found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
