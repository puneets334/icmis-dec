<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cases</title>
    <style>
        table {
            width: 100%;
            background: #f6fbf0;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>

<h2>Case Listings</h2>

<table>
    <thead>
        <tr>
            <th>Diary No</th>
            <th>Filing Date</th>
            <th>Purpose</th>
            <th>Details</th>
            <!-- Add other necessary columns -->
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($cases)): ?>
            <?php foreach ($cases as $case): ?>
                <tr>
                    <td><?= $case->diary_no; ?></td>
                    <td><?= $case->fil_dt; ?></td>
                    <td><?= $case->purpose; ?></td>
                    <td><!-- Add actions or details --></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No cases found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
