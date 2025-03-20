<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Listing Result</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>"> <!-- Adjust the path as necessary -->
</head>
<body>
    <div class="container">
        <h1>Case Listing Result</h1>
        <table border="1">
            <thead>
                <tr>
                    <th>Diary No</th>
                    <th>Short Description</th>
                </tr>
            </thead>
            <tbody>
                <?= $output ?: '<tr><td colspan="2">No cases found.</td></tr>' ?>
            </tbody>
        </table>
        <p>Total Cases Listed: <?= $total_case_listed ?></p>
        <a href="<?= site_url('caselisting') ?>">Back to Form</a>
    </div>
</body>
</html>
