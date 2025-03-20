<?= view('header') ?>
<?php helper('form'); ?>

<style>
    .center-text {
        text-align: center;
        margin-top: 20px;
    }
</style>

<body>
    <div class="container">
        <div class="center-text">
            <h3>CASES VERIFICATION REPORT AS ON <?= date('d-m-Y'); ?></h3>
        </div>
        
        <?php if (!empty($results)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="card-header">
                        <tr>
                            <th>SNo</th>
                            <th>Section Name</th>
                            <th>User Name</th>
                            <th>Employee ID</th>
                            <th>Verified</th>
                            <th>Not Verified</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sno = 1;
                        $verifiedTotal = 0;
                        $notVerifiedTotal = 0;
                        $grandTotal = 0;

                        foreach ($results as $row): 
                            $rowClass = $sno % 2 == 1 ? 'table-secondary' : 'table-light'; // Alternate row coloring
                        ?>
                        <tr class="<?= $rowClass; ?>">
                            <td class="text-right"><?= $sno; ?></td>
                            <td><?= esc($row['section_name']); ?></td>
                            <td><?= esc($row['name']); ?></td>
                            <td><?= esc($row['empid']); ?></td>
                            <td class="text-right"><?= esc($row['verified']); $verifiedTotal += $row['verified']; ?></td>
                            <td class="text-right"><?= esc($row['notverified']); $notVerifiedTotal += $row['notverified']; ?></td>
                            <td class="text-right"><?= esc($row['verified'] + $row['notverified']); $grandTotal += $row['verified'] + $row['notverified']; ?></td>
                        </tr>
                        <?php 
                            $sno++;
                        endforeach; 
                        ?>
                        <tr>
                            <td colspan="4" class="text-right"><b>TOTAL</b></td>
                            <td class="text-right"><b><?= $verifiedTotal; ?></b></td>
                            <td class="text-right"><b><?= $notVerifiedTotal; ?></b></td>
                            <td class="text-right"><b><?= $grandTotal; ?></b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="center-text alert alert-warning">No Records Found</div>
        <?php endif; ?>
    </div>
</body>
