<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judge Allocation Data</title>
    <style>
        .your-table-class {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Judge Allocation Data</h1>

        <div class="card">
            <div class="card-header">
                <h2>Purpose Data</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover your-table-class">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Priority</th>
                                <th>Mandatory</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($purposeData as $row): ?>
                                <tr>
                                    <td><?= $row['code'] ?></td>
                                    <td><?= $row['priority'] ?></td>
                                    <td><?= $row['mand'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h2>Allocation Data</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover your-table-class">
                        <thead>
                            <tr>
                                <th>Diary No</th>
                                <th>RID</th>
                                <th>Submaster ID</th>
                                <th>Next Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allocationData as $allocationRows): ?>
                                <?php if (is_array($allocationRows)): ?>
                                    <?php foreach ($allocationRows as $row): ?>
                                        <tr>
                                            <td><?= $row['diary_no'] ?></td>
                                            <td><?= $row['rid'] ?></td>
                                            <td><?= $row['submaster_id'] ?></td>
                                            <td><?= $row['next_dt'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
</html>