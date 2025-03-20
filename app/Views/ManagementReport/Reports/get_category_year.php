<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category & Year Wise Detailed Report</title>
    <style>
        #customers {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
        }

        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>

<body>
    <div id="r_box" style="text-align: center;">
        <h3 style="text-align:center;">Category & Year Wise Detailed Pendency Report (including defects) as on : <?= date('d-m-Y H:i:s'); ?></h3>
        <table id="customers">
            <thead>
                <tr style="background: #A9A9A9;">
                    <th>SrNo.</th>
                    <th>Category Code</th>
                    <th>Category Name</th>
                    <th>Total</th>
                    <th>Upto 1990</th>
                    <?php for ($i = 1991; $i <= 2021; $i++): ?>
                        <th><?= $i ?></th>
                    <?php endfor; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($categoryReportData) && is_array($categoryReportData)): ?>
                    <?php $sno = 1; ?>
                    <?php foreach ($categoryReportData as $row): ?>
                        <?php if (is_null($row['subcode2'])): ?>
                            <tr>
                                <td colspan="3"><strong>Total Pendency</strong></td>
                                <td><?= $row['gt'] ?></td>
                                <td><?= $row['upto_1990'] ?></td>
                                <?php for ($i = 1991; $i <= 2021; $i++): ?>
                                    <td><?= $row["year_" . $i] ?></td>
                                <?php endfor; ?>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td><?= $sno++; ?></td>
                                <td><?= is_null($row['subcode2']) ? $row['org_subcode1'] . '00' : $row['subcode1'] ?></td>
                                <td><?= $row['sub_name1'] ?></td>
                                <td><?= $row['gt'] ?></td>
                                <td><?= $row['upto_1990'] ?></td>
                                <?php for ($i = 1991; $i <= 2021; $i++): ?>
                                    <td><?= $row["year_" . $i] ?></td>
                                <?php endfor; ?>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="100%">No records found</td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
        <button onclick="window.print()">Print Report</button>
    </div>
</body>

</html>